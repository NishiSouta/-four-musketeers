<?php
session_start();
require 'db-connect.php';

// リクエストがPOSTでなければエラー
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'], $_POST['post_id'])) {
    echo '評価情報が不足しています。';
    exit;
}

$user_id = $_SESSION['user_id'];  // 現在ログインしているユーザーID
$participant_id = $_POST['user_id'];  // 評価対象のユーザーID
$post_id = $_POST['post_id'];  // 投稿ID

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // good_countの増加
    $updateGoodCount = $pdo->prepare('UPDATE user SET good_count = good_count + 1 WHERE user_id = ?');
    $updateGoodCount->execute([$participant_id]);

    // 成功メッセージ
    echo '評価完了';
} catch (PDOException $e) {
    echo 'エラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
