<?php 
session_start();
require 'db-connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $user_id = $_SESSION['user']['user_id'];
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $self_intro = htmlspecialchars($_POST['self_intro'], ENT_QUOTES, 'UTF-8');
        $place = htmlspecialchars($_POST['place'], ENT_QUOTES, 'UTF-8');

        // ユーザー情報を更新するクエリ
        $sql = "UPDATE user SET user_name = ?, self_intro = ?, place = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $self_intro, $place, $user_id]);

        // 興味のあるスポーツを更新する処理
        $sports = ['baseball', 'jogging', 'tennis', 'valley', 'soccer', 'basket', 'tabletennis', 'badminton', 'muscle', 'boxing', 'golf', 'football'];
        foreach ($sports as $sport) {
            if (isset($_POST[$sport])) {
                $level = htmlspecialchars($_POST[$sport . '_level'], ENT_QUOTES, 'UTF-8');
                $sql = "INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE level = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id, $sport, $level, $level]);
            }
        }

        // プロフィール画面にリダイレクト
        header("Location: myprofile.php");
        exit();

    } catch (PDOException $e) {
        echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>
