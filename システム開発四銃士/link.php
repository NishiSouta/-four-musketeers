<?php session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>募集登録一覧画面</title>
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
	  <th><a href="bosyuu-touroku.php?sport=1"><img src="images/baseball.jpg" alt=""><br>野球</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=2"><img src="images/run.jpg" alt=""><br>ジョギング</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=3"><img src="images/tennis.jpg" alt=""><br>テニス</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=4"><img src="images/soccer.jpg" alt=""><br>サッカー</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=5"><img src="images/basketball.jpg" alt=""><br>バスケットボール</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=6"><img src="images/table_tennis.jpg" alt=""><br>卓球</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=7"><img src="images/badminton.jpg" alt=""><br>バドミントン</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=8"><img src="images/gym.jpg" alt=""><br>筋トレ</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=9"><img src="images/boxing.jpg" alt=""><br>ボクシング</a></th>
	
	  <th><a href="bosyuu-touroku.php?sport=10"><img src="images/golf.jpg" alt=""><br>ゴルフ</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu-touroku.php?sport=11"><img src="images/american_football.jpg" alt=""><br>アメリカンフットボール</a></th>
	

	  <th><a href="bosyuu-touroku.php?sport=12"><img src="images/volleyball.jpg" alt=""><br>バレーボール</a></th>
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
