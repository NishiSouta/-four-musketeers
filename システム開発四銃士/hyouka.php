<?php 
session_start();
require 'db-connect.php'; 

// post_idをGETパラメータから取得
if (!isset($_GET['post_id'])) {
    echo '投稿IDが指定されていません。';
    exit;
}
$post_id = $_GET['post_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 参加者情報を取得するクエリ（participation、user、postテーブルを結合）
    $sql = $pdo->prepare('
        SELECT u.user_id, u.user_name, u.profile_image, u.bio
        FROM participation p
        JOIN user u ON p.user_id = u.user_id
        JOIN post pt ON p.post_id = pt.post_id
        WHERE p.post_id = ?
    ');
    $sql->execute([$post_id]);
    
    // 参加者がいる場合
    $participants = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    if ($participants) {
        echo '<h2>参加メンバー</h2>';
        echo '<ul>';
        foreach ($participants as $participant) {
            // 参加者の名前とプロフィール画像を表示
            $profile_img = isset($participant['profile_image']) ? 'uploads/' . htmlspecialchars($participant['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
            echo '<li>';
            echo '<img alt="プロフィール画像" src="' . $profile_img . '" class="avatar">';
            echo '<strong>' . htmlspecialchars($participant['user_name'], ENT_QUOTES, 'UTF-8') . '</strong><br>';
            // 評価ボタンを表示
            echo '<a href="evaluate.php?user_id=' . htmlspecialchars($participant['user_id'], ENT_QUOTES, 'UTF-8') . '&post_id=' . htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8') . '">評価する</a>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo 'この投稿に参加しているメンバーはありません。';
    }
} catch (PDOException $e) {
    echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
