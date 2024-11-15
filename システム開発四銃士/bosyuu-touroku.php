<?php session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集入力画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ここにサイト説明を入れます">
  <meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
  <link rel="stylesheet" href="css/style-bosyuu-touroku.css">
</head>
<body>
  <div id="container">
    <header>
      <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
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
    
    $profile_img = isset($user['profile_image']) ? 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
    echo '<img alt="image" src="' . $profile_img . '" class="avatar">';
	?>
		</aside>
		</header>
    <?php 	
} else {//ログアウト時のヘッダーを書く
  echo '<aside id="header-img"><a href="login.php"><img src="images/account_circle.png" alt=""></a></aside>';
	echo '</aside></header>';
}
    ?>
    <nav id="batu">
      <a href="link.php"><img src="images/batu.png" alt="×（バツ）"></a>
    </nav>
    <div id="contents">
      <div id="main">
        <section>
          <h2>
            <?php 
            // URLパラメータからスポーツ名を取得して表示
            if (isset($_GET['sport'])) {
              echo htmlspecialchars($_GET['sport'], ENT_QUOTES, 'UTF-8');
            } else {
              echo 'スポーツ名が指定されていません';
            }
            ?>
          </h2>
          <form action="bosyuu-touroku-kannryou.php" method="post">
            募集タイトル<br>
            <input type="text" name="bosyuutaitoru"><br>
            開催日時<br>
            <input name="event_datetime_from" type="date" />から<br>
            <input name="event_datetime_to" type="date" />まで<br>
            条件<br>
            募集する人数
            <select name="recruit_number">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select><br>
            すでに集まっている人数
            <select name="current_number">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select><br>
            実施場所<br>
            <input type="text" name="location"><br> <!-- 修正: name属性をlocationに -->
            参加費<br>
            <select name="participation_fee"> <!-- 修正: name属性をparticipation_feeに -->
              <option value="0">無料</option>
              <option value="500">500円以内</option>
              <option value="1000">1000円以内</option>
              <option value="2000">2000円以内</option>
              <option value="3000">3000円以内</option>
              <option value="4000">4000円以内</option>
              <option value="5000">5000円以内</option>
              <option value="6000">6000円以内</option>
              <option value="7000">7000円以内</option>
              <option value="8000">8000円以内</option>
              <option value="9000">9000円以内</option>
              <option value="10000">10000円以内</option>
              <option value="15000">15000円以内</option>
              <option value="20000">20000円以内</option>
              <option value="25000">25000円以内</option>
              <option value="30000">30000円以内</option>
            </select><br>
            <input type="checkbox" id="syosinsya" name="syosinsya" value="ok" />初心者OK<br>
            その他<br>
            <input type="text" name="description">
            <div id="center">
              <p><input type="submit" value="募集する" id="button"></p>
            </div>
          </form>
        </section>
      </div>
    </div>
    <footer>
      <small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
    </footer>
  </div>
</body>
</html>
