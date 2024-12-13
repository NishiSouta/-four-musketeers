<?php
// セッション開始
session_start();

// DB接続情報
require 'db-connect.php';

try {
    // PDOインスタンスを作成
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// セッションからユーザーIDを取得
$user_id = $_SESSION['user_id'];

// GETリクエストから投稿IDを取得
$post_id = isset($_GET['post_id']);


try {
    // 削除クエリ
    $delete_sql = "DELETE FROM post WHERE post_id = :post_id";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: toukou-itiran.php"); // 削除後に一覧ページへリダイレクト
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
