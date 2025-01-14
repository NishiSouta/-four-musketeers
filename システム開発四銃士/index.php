<?php session_start(); 
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>ホーム画面</title>
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

<?php require 'header.php'; ?>

<!--スライドショー-->
<aside id="mainimg">
<img src="images/baseball_img.jpg" alt="" class="slide0">
<img src="images/run_img.jpg" alt="" class="slide1">
<img src="images/soccer_img.jpg" alt="" class="slide2">
<img src="images/basketball_img.jpg" alt="" class="slide3">
</aside>
<div id="contents">

<div id="main">

<section>


<h3>おすすめ参加ランキング</h3>
<div id="iti">
<img src="images/1_1.png" alt="" class="img-item">
<a href="toukou-syousai.php?sport=ジョギング">
        <img src="images/run.jpg" alt="" class="img-item">ジョギング
    </a>
</div>


<hr>

<div id="ni">
<img src="images/2_2.png" alt="" class="img-item">
<a href="toukou-syousai.php?sport=野球">
		<img src="images/baseball.jpg" alt=""class="img-item">野球
	</a>
</div>

<hr>

<div id="san">
<img src="images/3_3.png" alt="" class="img-item">
<a href="toukou-syousai.php?sport=サッカー">
		<img src="images/soccer.jpg" alt=""class="img-item">サッカー
	</a>
</div>

<hr>


<h3>おすすめ募集ランキング</h3>
<div id="iti">
    <img src="images/1_1.png" alt="" class="img-item">
    <a href="bosyuu-touroku.php?sport=<?php echo urlencode(1); ?>">
        <img src="images/baseball.jpg" alt="" class="img-item">野球
    </a>
</div>
<hr>
<div id="ni">
    <img src="images/2_2.png" alt="" class="img-item">
    <a href="bosyuu-touroku.php?sport=<?php echo urlencode(4); ?>">
        <img src="images/soccer.jpg" alt="" class="img-item">サッカー
    </a>
</div>

<hr>

<div id="san">
<img src="images/3_3.png" alt="" class="img-item">
    <a href="bosyuu-touroku.php?sport=<?php echo urlencode(12); ?>">
        <img src="images/volleyball.jpg" alt="" class="img-item">バレー
    </a>
</div>

<hr>
</section>

<section id="new">
<h2>更新情報・お知らせ</h2>
<dl>
<dt>2024/11/12</dt>
<dd>12月本格稼働予定！  link sports<span class="newicon">NEW</span></dd>
<dt>2024/10/21</dt>
<dd>コーディング開始！</dd>
<dt>2024/09/17</dt>
<dd>link sportsを企画！</dd>
</dl>
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
