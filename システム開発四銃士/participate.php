<?php
session_start();
require 'db-connect.php';

// POSTデータの受け取り
$post_id = $_POST['post_id'] ?? null;

if (!$post_id) {
    echo "投稿IDが指定されていません。";
    exit;
}

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 募集人数と現在の参加人数を取得
    $sql = 'SELECT recruit_number, current_number FROM post WHERE post_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "指定された投稿が見つかりません。";
        exit;
    }

    $recruit_number = (int)$post['recruit_number'];
    $current_number = (int)$post['current_number'];

    // 募集人数がまだ残っているか確認
    if ($recruit_number > 0) {
        // recruit_numberを1減らし、current_numberを1増やす
        $sql = 'UPDATE post SET recruit_number = recruit_number - 1, current_number = current_number + 1 WHERE post_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id]);
    }

    $backURL = $_SERVER['HTTP_REFERER']; // 前のページのURLを取得

    header("Location: " . $backURL);  // メッセージ送信後のリダイレクト先を適切に変更
    exit;

} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}
?>
