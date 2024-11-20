<?php session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>投稿募集詳細画面</title>
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
<h2>野球の募集</h2>


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
