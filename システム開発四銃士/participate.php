<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'] ?? null;

    if (!$post_id) {
        echo "投稿IDが指定されていません。";
        exit;
    }

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 既に参加していないか確認
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM participation WHERE post_id = ? AND user_id = ?');
        $stmt->execute([$post_id, $user_id]);
        $isParticipated = $stmt->fetchColumn();

        if ($isParticipated) {
            echo "すでに参加しています。";
        } else {
            // 最大参加可能人数を取得
            $stmt = $pdo->prepare('SELECT current_number, recruit_number, max_capacity FROM post WHERE post_id = ?');
            $stmt->execute([$post_id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            //userテーブル更新 参加回数
            $sql = "UPDATE user SET participation_count = participation_count + 1 WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            
            // 現在の参加人数が最大参加可能人数を超えていないか確認
            if ($post['recruit_number'] == 0 or $post['current_number'] + $post['recruit_number'] > $post['max_capacity']) {
                // 参加データを挿入
                $stmt = $pdo->prepare('INSERT INTO participation (post_id, user_id) VALUES (?, ?)');
                $stmt->execute([$post_id, $user_id]);

                // 募集人数が0人でも参加を許可し、現在の参加人数を増やす
                $update_sql = $pdo->prepare('UPDATE post SET current_number =  current_number + 1 WHERE post_id = ?');
                $update_sql->execute([$post_id]);
            } else {
                // 参加データを挿入
                $stmt = $pdo->prepare('INSERT INTO participation (post_id, user_id) VALUES (?, ?)');
                $stmt->execute([$post_id, $user_id]);
        
                // 募集人数を減らし、現在の参加人数を増やす
                $update_sql = $pdo->prepare('UPDATE post SET current_number = current_number + 1, recruit_number = recruit_number - 1 WHERE post_id = ?');
                $update_sql->execute([$post_id]);
            }
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;

    } catch (PDOException $e) {
        die("データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
} else {
    echo "無効なリクエストです。";
    exit;
}
