<?php
session_start();
require 'db-connect.php';

$message = $_POST["message"];
$users_id = $_SESSION["user_id"];
//$chat_id =$_POST["chat_id"];
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}

$chat_id = 1; // Replace with the appropriate chat ID
$stmt = $pdo->prepare("INSERT INTO message (message_id, chat_id, user_id, message_text, sent_at) VALUES (NULL, :chat_id, :user_id, :message_text, NOW())");
$stmt->execute([
    ':chat_id' => $chat_id,
    ':user_id' => $users_id,
    ':message_text' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
]);


header("Location: index.php");
exit;
