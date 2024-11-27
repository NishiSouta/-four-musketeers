<?php
session_start();
require 'db-connect.php';


// 必要なパラメータが渡されているか確認
 if (!isset($_GET['post_id'])) {
    echo "投稿IDが指定されていません。";
    exit;
}

$post_id = $_GET['post_id'];


try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 指定された投稿IDのデータを取得
    $sql = $pdo->prepare('SELECT * FROM post WHERE post_id = ?');
    $sql->execute([$post_id]);
    $post = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "指定された投稿が見つかりません。";
        exit;
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集投稿詳細画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style-bosyuu-toukou-syousai.css"> <!-- デザインに合うCSSを指定 -->
</head>
<body>
<div id="container">
  <header>
    <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Link Sports"></a></h1>
    <nav id="close-icon">
      <a href="index.php"><img src="images/batu.png" alt="閉じる"></a>
    </nav>
  </header>

  <div id="content">
    <div class="image-banner">
      <img src="images/baseball_img.jpg" alt="野球バナー"> <!-- サンプル画像 -->
    </div>
    
    <section class="post-details">
      <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
      <p class="category">
        <img src="images/baseball.jpg" alt="カテゴリアイコン">
        野球</p>
      <hr>
      <div class="detail-item">
        <strong>開催日時</strong><br>
        <?php 
          echo htmlspecialchars(date('Y年n月j日（D） H:i', strtotime($post['event_datetime_from'])), ENT_QUOTES, 'UTF-8') . "～" . 
               htmlspecialchars(date('H:i', strtotime($post['event_datetime_to'])), ENT_QUOTES, 'UTF-8');
        ?>
      </div>
      <div class="detail-item">
        <strong>場所</strong><br>
        <?php echo htmlspecialchars($post['location'], ENT_QUOTES, 'UTF-8'); ?>
      </div>
      <div class="detail-item">
        <strong>参加費</strong><br>
        <?php echo ($post['participation_fee'] == 0) ? "無料" : htmlspecialchars($post['participation_fee'], ENT_QUOTES, 'UTF-8') . "円以内"; ?>
      </div>
      <div class="detail-item">
        <strong>募集人数</strong><br> 
        <?php echo htmlspecialchars($post['recruit_number'], ENT_QUOTES, 'UTF-8'); ?>人
      </div>
      <div class="detail-item">
        <strong>すでに集まっている人数</strong><br>
        <?php echo htmlspecialchars($post['current_number'], ENT_QUOTES, 'UTF-8'); ?>人
      </div>
      <div class="detail-item">
        <strong>その他</strong><br>
        <?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?>
      </div>
      <hr>
    </section>
  </div>

  <footer>
    <small>&copy; Link Sports All Rights Reserved.</small>
  </footer>
</div>
</body>
</html>
