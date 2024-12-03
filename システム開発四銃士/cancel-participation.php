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

    // 募集人数を更新
    $update_sql = $pdo->prepare('UPDATE post SET current_number = current_number - 1, recruit_number = recruit_number + 1 WHERE post_id = ?');
    $update_sql->execute([$post_id]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} catch (PDOException $e) {
    die("エラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
