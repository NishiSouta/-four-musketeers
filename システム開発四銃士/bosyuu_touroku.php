<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>募集入力画面</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/style.css">
<script src="js/openclose.js"></script>
<script src="js/fixmenu_pagetop.js"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

<div id="container">

	<header>
		<h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
		<aside id="header-img"><a href="login.php"><img src="images/account_circle.png" alt=""></a></aside>
		</header>

<!--PC用（901px以上端末）メニュー-->
<nav id="menubar">
	<ul>
	<li><a href="index.html">ホーム</a></li>
	<li class="current"><a href="about.html">プロフィール</a></li>
	<li><a href="gallery.html">投稿一覧</a></li>
	<li><a href="link.html">募集一覧</a></li>
	<li><a href="contact.html">ログアウト</a></li>
	</ul>
	</nav>
	
	<!--小さな端末用（900px以下端末）メニュー-->
	<nav id="menubar-s">
	<a href="link.html"><img src="images/batu.png" alt="×（ばつ）"></a>
	</nav>

<div id="contents">

<div id="main">

<section>

<h2>募集可能なスポーツ</h2>

<table class="ta1">
	<tr>
	  <th><a href="bosyuu_touroku.php" id="baseball"><img src="images/baseball.jpg" alt=""><br>野球</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="jogging"><img src="images/run.jpg" alt=""><br>ジョギング</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="tennis"><img src="images/tennis.jpg" alt=""><br>テニス</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="volleyball"><img src="images/volleyball.jpg" alt=""><br>バレーボール</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="soccer"><img src="images/soccer.jpg" alt=""><br>サッカー</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="basketball"><img src="images/basketball.jpg" alt=""><br>バスケットボール</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="table_tennis"><img src="images/table_tennis.jpg" alt=""><br>卓球</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="badminton"><img src="images/badminton.jpg" alt=""><br>バドミントン</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="gym"><img src="images/gym.jpg" alt=""><br>筋トレ</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="boxing"><img src="images/boxing.jpg" alt=""><br>ボクシング</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="golf"><img src="images/golf.jpg" alt=""><br>ゴルフ</a></th>
	</tr>
	<tr>
	  <th><a href="bosyuu_touroku.php" id="american_football"><img src="images/american_football.jpg" alt=""><br>アメリカンフットボール</a></th>
	</tr>
  </table>
  

</section>

</div>
<!--/main-->

</div>
<!--/contents-->

</div>
<!--/container-->

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
