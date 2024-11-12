<?php session_start(); ?>
<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>プロフィール画面（ユーザー同士側）</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/myprofile-edit.css">
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
    <aside id="header-img"><a href="login-input.php"><img src="images/account_circle.png" alt=""></a></aside>
    </header>

<!--PC用（901px以上端末）メニュー-->
<nav id="menubar">
<ul>
<li><a href="index.html">ホーム</a></li>
<li class="current"><a href="myprofile.html">プロフィール</a></li>
<li><a href="gallery.html">投稿一覧</a></li>
<li><a href="link.html">募集一覧</a></li>
<li><a href="contact.html">ログアウト</a></li>
</ul>
</nav>

<!--小さな端末用（900px以下端末）メニュー-->
<nav id="menubar-s">
<ul>
<li><a href="index.html">ホーム</a></li>
<li><a href="myprofile.php">プロフィール</a></li>
<li><a href="gallery.html">投稿一覧</a></li>
<li><a href="link.html">募集一覧</a></li>
<li><a href="contact.html">ログアウト</a></li>
</ul>
</nav>

  <div id="contents">
    <div id="main">

      <?php
      try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = 7');
        //$sql->execute([$_SESSION['user']['user_id']]);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $profile_img = isset($row['profile_img']) ? htmlspecialchars($row['profile_img'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
            $self_intro = isset($row['self_intro']) ? htmlspecialchars($row['self_intro'], ENT_QUOTES, 'UTF-8') : '';
            $sex = isset($row['sex']) ? htmlspecialchars($row['sex'], ENT_QUOTES, 'UTF-8') : '未設定';
            $place = isset($row['place']) ? htmlspecialchars($row['place'], ENT_QUOTES, 'UTF-8') : '';
  
            echo '<form action="myprofile-edit.php" method="post">';
            echo '<div id="user_icon" class="user-icon-container">';
            echo '<img alt="image" src="' . $profile_img . '" id="profileImage">';
            echo '<input type="file" id="imageUpload" style="display: none;">';
            echo '<button type="button" id="uploadButton">+</button>';
            echo '</div><br>';
            echo '<input type="text" name="name" value="' . htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') . '"></p>';
            echo '<textarea name="self_intro" placeholder="自己紹介" cols="32" rows="10">' . $self_intro . '</textarea><br><br>';
            echo '<span class="info">登録情報</span><br><br>';
            echo '<div id="age-container">';
            echo '<div id="age-sub">年齢</div>';
            echo '<div id="age-main">' . htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') . '</div>';
            echo '</div><hr>';
            echo '<div id="sex-container">';
            echo '<div id="sex-sub">性別</div>';
            echo '<div id="sex-main">' . $sex . '</div>';
            echo '</div><hr>';
            echo '<div id="place-container">';
            echo '<div id="place-sub">都道府県</div>';
            echo '<div id="place-main">';
            echo '<select name="place" id="place_name">';
            $prefectures = ["北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県", "茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県", "新潟県", "富山県", "石川県", "福井県", "山梨県", "長野県", "岐阜県", "静岡県", "愛知県", "三重県", "滋賀県", "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県", "鳥取県", "島根県", "岡山県", "広島県", "山口県", "徳島県", "香川県", "愛媛県", "高知県", "福岡県", "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県"];
            foreach ($prefectures as $prefecture) {
              $selected = ($place == $prefecture) ? ' selected' : '';
              echo '<option value="' . $prefecture . '"' . $selected . '>' . $prefecture . '</option>';
            }
            echo '</select>';
            echo '</div></div><hr>';
  
            echo '<div id="profile_info_3"></div>';
            echo '<br><p>興味のあるスポーツ</p>';
            echo '<form>';
            $sports = ['baseball' => '野球', 'jogging' => 'ジョギング', 'tennis' => 'テニス', 'valley' => 'バレーボール', 'soccer' => 'サッカー', 'basket' => 'バスケットボール', 'tabletennis' => '卓球', 'badminton' => 'バドミントン', 'muscle' => '筋トレ', 'boxing' => 'ボクシング', 'golf' => 'ゴルフ', 'football' => 'アメリカンフットボール'];
            $levels = ['未設定', '初心者', '中級者', '上級者'];
            foreach ($sports as $key => $sport) {
              echo '<label>';
              echo '<input type="checkbox" class="sport-checkbox" data-target="' . $key . '-level"> ' . $sport;
              echo '</label>';
              echo '<div id="' . $key . '-level" class="level-buttons">';
              foreach ($levels as $level) {
                echo '<button type="button" class="level-btn">' . $level . '</button>';
              }
              echo '</div><hr>';
            }
            echo '<button type="submit">この内容で決定</button>';
            echo '</form>';
            echo '</div><!--/profile_info_3-->';
            echo '</form>';
          } else {
            echo 'ユーザー情報が見つかりませんでした。';
          }
        } catch (PDOException $e) {
          echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
        ?>

    </div><!-- /main -->
  </div><!-- /contents -->
  
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


document.querySelectorAll('.sport-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                if (this.checked) {
                    target.style.display = 'block';
                } else {
                    target.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.level-btn').forEach(button => {
            button.addEventListener('click', function() {
                const buttons = this.parentElement.querySelectorAll('.level-btn');
                buttons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.getElementById('uploadButton').addEventListener('click', function() {
    document.getElementById('imageUpload').click();
});

document.getElementById('profileImage').addEventListener('click', function() {
    document.getElementById('imageUpload').click();
});

document.getElementById('imageUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

</script>

</body>
</html>