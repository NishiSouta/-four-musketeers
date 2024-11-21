<?php
session_start();
require 'db-connect.php';

try {
  $pdo = new PDO($connect, USER, PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('データベース接続失敗: ' . $e->getMessage());
}

// セッションからユーザーIDを取得
if (!isset($_SESSION['user_id'])) {
  die('ログインしてください。');
}
$user_id = $_SESSION['user_id'];

// フォームデータを取得
$title = htmlspecialchars($_POST['bosyuutaitoru'], ENT_QUOTES, 'UTF-8');


if (!isset($_GET['sport'])) {
  die('スポーツIDが指定されていません');
}
$sport_id = (int) $_GET['sport']; // 整数として取得


$date_from = $_POST['event_datetime_from'];
$date_to = $_POST['event_datetime_to'];
$ninzuu = $_POST['recruit_number'];
$current_ninzuu = $_POST['current_number'];
$zissibasyo = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
$sum = $_POST['participation_fee'];
$syosinsya = isset($_POST['syosinsya']) ? $_POST['syosinsya'] : 'no';
$sonota = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

// SQL クエリ実行
$sql = "INSERT INTO post (user_id, sport_id, title, event_datetime_from, event_datetime_to, recruit_number, current_number, location, participation_fee, syosinsya, description) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $sport_id, $title, $date_from, $date_to, $ninzuu, $current_ninzuu, $zissibasyo, $sum, $syosinsya, $sonota]);


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
  <?php require 'header.php'; ?>

    <div id="contents">
      <div id="main">
        <section>
          募集しました。
          <p><input type="button" value="TOPページへ" id="button" onclick="location.href='index.php'"></p>
        </section>
      </div>
    </div>
    <footer>
      <small>Copyright© <a href="index.php">Photo Gallery</a> All Rights Reserved.</small>
    </footer>
  </div>
</body>
</html>
