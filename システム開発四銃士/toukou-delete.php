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
$user_id = $_SESSION['user_id'] ?? null;

// GETリクエストから投稿IDを取得
if (!isset($_GET['post_id'])) {
    echo "投稿IDが指定されていません。";
    exit;
}
$post_id = intval($_GET['post_id']); // 整数に変換して安全性を確保

try {
    // 投稿の所有者を確認
    $check_sql = "SELECT user_id FROM post WHERE post_id = :post_id";
    $stmt = $pdo->prepare($check_sql);
    $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $author_id = $stmt->fetchColumn();

    if (!$author_id) {
        echo "指定された投稿が存在しません。";
        exit;
    }

    if ($user_id !== $author_id) {
        echo "この投稿を削除する権限がありません。";
        exit;
    }

    // 削除クエリ
    $delete_sql = "DELETE FROM post WHERE post_id = :post_id";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();

    // 削除後に一覧ページへリダイレクト
    header("Location: toukou-itiran.php");
    exit;
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}
