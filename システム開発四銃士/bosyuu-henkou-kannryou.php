<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 必要なデータが全てPOSTされているか確認
    if (
        isset($_POST['post_id'], $_POST['bosyuutaitoru'], $_POST['event_datetime_from'], 
        $_POST['event_datetime_to'], $_POST['recruit_number'], $_POST['current_number'], 
        $_POST['location'], $_POST['participation_fee'], $_POST['description'])
    ) {
        $post_id = $_POST['post_id'];
        $title = $_POST['bosyuutaitoru'];
        $event_datetime_from = $_POST['event_datetime_from'];
        $event_datetime_to = $_POST['event_datetime_to'];
        $recruit_number = $_POST['recruit_number'];
        $current_number = $_POST['current_number'];
        $location = $_POST['location'];
        $participation_fee = $_POST['participation_fee'];
        $description = $_POST['description'];
        $syosinsya = isset($_POST['syosinsya']) ? 'ok' : 'no'; // 初心者OKフラグ

        try {
            // データベース接続
            $pdo = new PDO($connect, USER, PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // データ更新クエリ
            $sql = "UPDATE post 
                    SET title = :title, 
                        event_datetime_from = :event_datetime_from,
                        event_datetime_to = :event_datetime_to,
                        recruit_number = :recruit_number,
                        current_number = :current_number,
                        location = :location,
                        participation_fee = :participation_fee,
                        syosinsya = :syosinsya,
                        description = :description
                    WHERE post_id = :post_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':event_datetime_from', $event_datetime_from, PDO::PARAM_STR);
            $stmt->bindValue(':event_datetime_to', $event_datetime_to, PDO::PARAM_STR);
            $stmt->bindValue(':recruit_number', $recruit_number, PDO::PARAM_INT);
            $stmt->bindValue(':current_number', $current_number, PDO::PARAM_INT);
            $stmt->bindValue(':location', $location, PDO::PARAM_STR);
            $stmt->bindValue(':participation_fee', $participation_fee, PDO::PARAM_INT);
            $stmt->bindValue(':syosinsya', $syosinsya, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);

            // クエリ実行
            $stmt->execute();

            // 更新成功時のリダイレクト
            header('Location: toukou-itiran.php'); // 成功時の遷移先
            exit;

        } catch (PDOException $e) {
            // エラーハンドリング
            echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit;
        }
    } else {
        echo "必要なデータが不足しています。";
        exit;
    }
} else {
    echo "無効なリクエストです。";
    exit;
}
?>
