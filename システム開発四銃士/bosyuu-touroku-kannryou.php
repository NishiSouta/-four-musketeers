<?php
require 'db-connect.php';
session_start();
// データベース接続
try {
  $pdo = new PDO($connect, USER, PASS);
} catch (PDOException $e) {
  die('データベース接続失敗。' . $e->getMessage());
}

// フォームからのデータを取得
$title = htmlspecialchars($_POST['bosyuutaitoru'], ENT_QUOTES, 'UTF-8');
$date_from = $_POST['event_datetime_from'];
$date_to = $_POST['event_datetime_to'];
$ninzuu = $_POST['recruit_number'];
$current_ninzuu = $_POST['current_number'];
$zissibasyo = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
$sum = $_POST['participation_fee'];
$syosinsya = isset($_POST['syosinsya']) ? $_POST['syosinsya'] : 'no';
$sonota = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

// データベースに挿入
$sql = "INSERT INTO post (user_id, title, event_datetime_from, event_datetime_to, recruit_number, current_number, location, participation_fee, syosinsya, description, created_at) VALUES (7, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $date_from, $date_to, $ninzuu, $current_ninzuu, $zissibasyo, $sum, $syosinsya, $sonota, date('Y-m-d H:i:s')]);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集入力画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ここにサイト説明を入れます">
  <meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
  <link rel="stylesheet" href="css/style-bosyuu-touroku-kannryou.css">
</head>
<body>
  <div id="container">
    <header>
      <h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
      <aside id="header-img"><a href="login.php"><img src="images/account_circle.png" alt=""></a></aside>
    </header>
    <nav id="menubar">
      <ul>
        <li><a href="index.html">ホーム</a></li>
        <li class="current"><a href="about.html">プロフィール</a></li>
        <li><a href="gallery.html">投稿一覧</a></li>
        <li><a href="link.php">募集する</a></li>
        <li><a href="contact.html">ログアウト</a></li>
      </ul>
    </nav>
    <nav id="menubar-s">
      <ul>
        <li><a href="index.html">ホーム</a></li>
        <li><a href="about.html">プロフィール</a></li>
        <li><a href="gallery.html">投稿一覧</a></li>
        <li><a href="link.php">募集する</a></li>
        <li><a href="contact.html">ログアウト</a></li>
      </ul>
    </nav>
    <div id="contents">
      <div id="main">
        <section>
          募集しました。
          <p><input type="button" value="TOPページへ" id="button" onclick="location.href='index.html'"></p>
        </section>
      </div>
    </div>
    <footer>
      <small>Copyright© <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
    </footer>
  </div>
</body>
</html>
