<?php
session_start();
require 'db-connect.php';
$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 参加情報を削除
$stmt = $pdo->prepare('DELETE FROM participation WHERE post_id = ? AND user_id = ?');
$stmt->execute([$post_id, $user_id]);

    //userテーブル更新 参加回数
$sql = "UPDATE user SET participation_count = participation_count - 1 WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

// 現在の募集人数と参加人数を取得
$stmt = $pdo->prepare('SELECT current_number, recruit_number, max_capacity FROM post WHERE post_id = ?');
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// 現在の参加人数が最大人数未満になった場合のみ、募集人数を増やす
if ($post['current_number'] -1 < $post['max_capacity']) {
    // 現在の参加人数を-1のみ実行
    $update_sql = $pdo->prepare('UPDATE post SET current_number = current_number - 1, recruit_number = recruit_number + 1 WHERE post_id = ?');

} else {
    // それ以外の場合、募集人数（recruit_number）を-1、現在の参加人数を-1
    $update_sql = $pdo->prepare('UPDATE post SET current_number = current_number - 1 WHERE post_id = ?');

}
$update_sql->execute([$post_id]);


    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} catch (PDOException $e) {
    die("エラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
