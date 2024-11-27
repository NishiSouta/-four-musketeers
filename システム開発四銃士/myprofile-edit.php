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

<?php require 'header.php'; ?> 
<?php if (isset($_SESSION['error'])) { echo '<p class="error">' . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . '</p>'; unset($_SESSION['error']); } ?>

  <div id="contents">
    <div id="main">

      <?php
      $user_id = $_SESSION['user_id'];
      try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$user_id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $profile_img = isset($row['profile_image']) ? 'uploads/'. htmlspecialchars($row['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
            $self_intro = isset($row['bio']) ? htmlspecialchars($row['bio'], ENT_QUOTES, 'UTF-8') : '';
            $place = isset($row['activity_region']) ? htmlspecialchars($row['activity_region'], ENT_QUOTES, 'UTF-8') : '';
  
            // 修正: フォームのactionをmyprofile-input.phpに修正し、画像アップロードに必要なenctypeを追加
            echo '<form action="myprofile-input.php" method="post" enctype="multipart/form-data">';
            echo '<div id="user_icon" class="user-icon-container">';
            echo '<img alt="image" src="' . $profile_img . '" id="profileImage">';
            echo '<input type="file" id="imageUpload" name="profile_img" style="display: none;">'; // nameを追加
            echo '<button type="button" id="uploadButton">+</button>';
            echo '</div><br>';
            echo '<div class=user_name>ユーザ名</div>';
            echo '<div class=name><input type="text" name="name" value="' . htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') . '"></p></div><br>';
            echo '<div class=self-introduction>自己紹介</div>';
            echo '<div class=self-introduction-main><textarea name="self_intro" placeholder="自己紹介" cols="32" rows="10">' . $self_intro . '</textarea><br><br></div>';
            echo '<span class="info">登録情報</span><br><br>';
            echo '<div id="age-container">';
            echo '<div id="age-sub">年齢</div>';
            echo '<div id="age-main">' . htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') . '</div>';
            echo '</div><hr>';
            echo '<div id="sex-container">';
            echo '<div id="sex-sub">性別</div>';
            echo '<div id="sex-main">' . $row['gender'] . '</div>';
            echo '</div><hr>';
            echo '<div id="place-container">';
            echo '<div id="place-sub">活動地域</div>';
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

            $sport_sql = $pdo->prepare('SELECT s.sport_name, us.level FROM user_sport us JOIN sport s ON us.sport_id = s.sport_id WHERE us.user_id = ?');
            $sport_sql->execute([$user_id]);
            $user_sports = [];
            while ($sport_row = $sport_sql->fetch(PDO::FETCH_ASSOC)) {
                $user_sports[$sport_row['sport_name']] = $sport_row['level'] ?: '未設定'; // レベルがNULLまたは空なら「未設定」
            }
            $sports = ['野球', 'ジョギング', 'テニス', 'バレーボール', 'サッカー', 'バスケットボール', '卓球', 'バドミントン', '筋トレ', 'ボクシング', 'ゴルフ', 'アメリカンフットボール'];
            
$levels = ['未設定', '初心者', '中級者', '上級者'];


// スポーツ選択部分の修正
foreach ($sports as $sport) {
  $isChecked = isset($user_sports[$sport]); // 配列にキーが存在するかをチェック
  $selectedLevel = $isChecked ? $user_sports[$sport] : '未設定'; // 未設定の場合のデフォルト値

  echo '<label>';
  echo '<input type="checkbox" class="sport-checkbox" data-target="' . $sport . '-level" name="sports[]" value="' . $sport . '"' . ($isChecked ? ' checked' : '') . '> ' . $sport;
  echo '</label>';
  echo '<div id="' . $sport . '-level" class="level-buttons"' . ($isChecked ? ' style="display: block;"' : ' style="display: none;"') . '>';
  foreach ($levels as $level) {
      $isSelected = ($selectedLevel === $level) ? ' selected' : '';
      echo '<button type="button" class="level-btn' . $isSelected . '" data-level="' . $level . '">' . $level . '</button>';
  }
  echo '<input type="hidden" name="levels[' . $sport . ']" value="' . $selectedLevel . '">';
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


// レベルボタンをクリックしたときに hidden input を更新
document.querySelectorAll('.level-btn').forEach(button => {
    button.addEventListener('click', function () {
        const buttons = this.parentElement.querySelectorAll('.level-btn');
        buttons.forEach(btn => btn.classList.remove('selected'));
        this.classList.add('selected');

        // 対応する hidden input を更新
        const hiddenInput = this.parentElement.querySelector('input[type="hidden"]');
        hiddenInput.value = this.getAttribute('data-level');
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
