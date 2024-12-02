<?php
session_start();
require 'db-connect.php';

$message = $_POST["message"];
$users_id = $_SESSION["user_id"];
$chat_id = $_POST["chat_id"];  // 送信されたchat_idを受け取る

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // メッセージを挿入するクエリ
    $stmt = $pdo->prepare("INSERT INTO message (message_id, chat_id, user_id, message_text, sent_at) VALUES (NULL, :chat_id, :user_id, :message_text, NOW())");
    $stmt->execute([
        ':chat_id' => $chat_id,
        ':user_id' => $users_id,
        ':message_text' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    ]);

    header("Location: index.php");  // メッセージ送信後のリダイレクト先を適切に変更
    exit;

} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}

