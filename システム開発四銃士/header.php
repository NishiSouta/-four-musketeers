<header>
<!-- ナビゲーションメニュー-->	
<h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
<!--ログイン-->
<aside id="header-img">
<?php
if (isset($_SESSION['user_id'])) {
	//ログイン時のヘッダーを書く
    $user_id = $_SESSION['user_id'];
    
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    
    $profile_img = isset($user['profile_image']) ? htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
    echo '<img alt="image" src="' . $profile_img . '" class="avatar">';
	?>
		</aside>
		</header>
	
<!--PC用（901px以上端末）メニュー-->
<nav id="menubar">
	<ul>
		<li><a href="index.php">ホーム</a></li>
		<li class="current"><a href="about.html">プロフィール</a></li>
		<li><a href="gallery.html">投稿一覧</a></li>
		<li><a href="link.php">募集する</a></li>
		<li><a href="contact.html">ログアウト</a></li>
	</ul>
</nav>	
	<!--小さな端末用（900px以下端末）メニュー-->
<nav id="menubar-s">
	<ul>
		<li><a href="index.php">ホーム</a></li>
		<li><a href="about.html">プロフィール</a></li>
		<li><a href="gallery.html">投稿一覧</a></li>
		<li><a href="link.php">募集する</a></li>
		<li><a href="contact.html">ログアウト</a></li>
	</ul>
</nav>
<?php 	
} else {//ログアウト時のヘッダーを書く
    echo 'ログインしてください。'; // セッションにユーザーIDがない場合のメッセージ
	echo '</aside></header>';
}
    ?>