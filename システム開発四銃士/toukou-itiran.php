<?php session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>投稿募集一覧画面</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/link.css">
<script src="js/openclose.js"></script>
<script src="js/fixmenu_pagetop.js"></script>
</head>

<body>

<div id="container">

<?php require 'header.php'; ?>

<div id="contents">
<div id="main">
<section>

<h2>募集可能なスポーツ</h2>

<table class="ta1">
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=野球"><img src="images/baseball.jpg" alt=""><br>野球</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=ジョギング"><img src="images/run.jpg" alt=""><br>ジョギング</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=テニス"><img src="images/tennis.jpg" alt=""><br>テニス</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=バレーボール"><img src="images/volleyball.jpg" alt=""><br>バレーボール</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=サッカー"><img src="images/soccer.jpg" alt=""><br>サッカー</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=バスケットボール"><img src="images/basketball.jpg" alt=""><br>バスケットボール</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=卓球"><img src="images/table_tennis.jpg" alt=""><br>卓球</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=バドミントン"><img src="images/badminton.jpg" alt=""><br>バドミントン</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=筋トレ"><img src="images/gym.jpg" alt=""><br>筋トレ</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=ボクシング"><img src="images/boxing.jpg" alt=""><br>ボクシング</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=ゴルフ"><img src="images/golf.jpg" alt=""><br>ゴルフ</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=アメリカンフットボール"><img src="images/american_football.jpg" alt=""><br>アメリカンフットボール</a></th>
	</tr>
  </table>

</section>

</div>

</div>
</div>

<footer>
<small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
<span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
</footer>

<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>

<!--メニュー開閉ボタン-->
<div id="menubar_hdr" class="close"></div>

<!--メニューの開閉処理条件設定　900px以下-->
<script>
if (OCwindowWidth() <= 900) {
	open_close("menubar_hdr", "menubar-s");
}
</script>

</body>
</html>
