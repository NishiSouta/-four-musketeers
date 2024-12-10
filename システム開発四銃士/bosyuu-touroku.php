<?php session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>募集入力画面</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/style-bosyuu-touroku.css">
      <script src="js/openclose.js"></script>
      <script src="js/fixmenu_pagetop.js"></script>
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
          }
	      ?>
		  </aside>
    
  <nav id="batu">
    <a href="link.php"><img src="images/batu.png" alt="×（バツ）"></a>
  </nav>
</header>


<div id="contents">
  <div id="main">
    <section>
      <h2>
        <?php 
          if (isset($_GET['sport'])) {
            // sport_idに応じたスポーツ名を表示
            $sports = [
              1 => '野球', 2 => 'ジョギング', 3 => 'テニス', 4 => 'サッカー',
              5 => 'バスケットボール', 6 => '卓球', 7 => 'バドミントン', 8 => '筋トレ',
              9 => 'ボクシング', 10 => 'ゴルフ', 11 => 'アメリカンフットボール', 12 => 'バレーボール'
            ];
            echo htmlspecialchars($sports[$_GET['sport']] ?? '不明なスポーツ', ENT_QUOTES, 'UTF-8');
          } else {
              echo 'スポーツ名が指定されていません';
          }
        ?>
      </h2>  
<form action="bosyuu-touroku-kannryou.php?sport=<?php echo htmlspecialchars($_GET['sport'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
       
       
  <div class="form-group">
    <label for="bosyuutaitoru">募集タイトル</label>
      <input type="text" id="bosyuutaitoru" name="bosyuutaitoru" required>
</div>


        <div class="form-group">
          <label for="event_datetime_from">開催日時</label><br>
            <input id="event_datetime_from" name="event_datetime_from" type="datetime-local" required> から<br>
            <input id="event_datetime_to" name="event_datetime_to" type="datetime-local" required> まで
        </div>


          <div class="form-group">
            <label for="recruit_number">募集する人数</label>
              <select id="recruit_number" name="recruit_number">
                  <?php for ($i = 1; $i <= 20; $i++) echo "<option value=\"$i\">$i</option>"; ?>
            </select>
          </div>


        <div class="form-group">
            <label for="current_number">すでに集まっている人数</label>
              <select id="current_number" name="current_number">
                <?php for ($i = 0; $i <= 20; $i++) echo "<option value=\"$i\">$i</option>"; ?>
              </select>
        </div>

        <div class="form-group">
          <label for="postal_code">郵便番号</label>
          <input type="text" id="postal_code" name="postal_code" maxlength="7" pattern="\d{7}" placeholder="例: 1234567">
          <button type="button" id="fetch-address" class="large-button">住所を自動入力</button>
        </div>
<!-- JavaScript -->
<script>
document.getElementById('fetch-address').addEventListener('click', function() {
  const postalCode = document.getElementById('postal_code').value;
  
  if (!/^\d{7}$/.test(postalCode)) {
    alert('正しい郵便番号を入力してください（7桁の数字）。');
    return;
  }

  fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${postalCode}`)
    .then(response => response.json())
    .then(data => {
      if (data.results) {
        const address = data.results[0].address1 + data.results[0].address2 + data.results[0].address3;
        document.getElementById('location').value = address;
      } else {
        alert('住所が見つかりませんでした。郵便番号を確認してください。');
      }
    })
    .catch(error => {
      console.error('エラー:', error);
      alert('住所の取得中にエラーが発生しました。');
    });
});
</script>

        <div class="form-group">
            <label for="location">実施場所</label>
              <input type="text" id="location" name="location" required>
        </div>


        <div class="form-group">
            <label for="participation_fee">参加費</label>
              <select id="participation_fee" name="participation_fee">
                <option value="0">無料</option>
                <?php for ($i = 1000; $i <= 30000; $i += 1000) echo "<option value=\"$i\">{$i}円以内</option>"; ?>
            </select>
        </div>


        <div class="form-group">
            <label for="syosinsya">
              <input type="checkbox" id="syosinsya" name="syosinsya" value="ok"> 初心者OK
            </label>
        </div>


        <div class="form-group">
            <label for="description">その他</label>
              <input type="text" id="description" name="description">
        </div>


        <div id="center">
            <input type="submit" value="募集する" id="button">
        </div>
      </form>
    </section>
  </div>
</div>


<footer>
    <small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
    <span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
  </footer>
</div>

<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>
<div id="menubar_hdr" class="close"></div>
</body>
</html>