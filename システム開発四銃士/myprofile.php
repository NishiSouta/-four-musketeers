<?php
session_start();
require 'db-connect.php';
?>
 
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>プロフィール画面（自分側）</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/myprofile.css">
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
 
  <div id="contents">
    <div id="main">
      <?php
      $user_id = $_SESSION['user_id'];
      $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
      try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$user_id]);
        foreach ($sql as $row) {
          echo '<div id="name">';
          echo '<button type="button" onclick="location.href=\'myprofile-edit.php\'">編集</button>';
          echo '<div id="name2">' . htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') . '</div>';
          echo '</div><br>';
          echo '<div id="user_icon">';
 
 
          $profile_img = isset($row['profile_image']) ? 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
          echo '<img alt="image" src="' . $profile_img . '" class="avatar">';
 
 
          echo '</div>';
          echo '<div id="counts">';
          echo '<span class="post">投稿:' . htmlspecialchars($row['post_count'], ENT_QUOTES, 'UTF-8') . '回</span>';
          echo '<span class="participation">参加:' . htmlspecialchars($row['participation_count'], ENT_QUOTES, 'UTF-8') . '回</span><br>';
          echo '<span class="good_count">good数:' . htmlspecialchars($row['good_count'], ENT_QUOTES, 'UTF-8') . '</span>';

          if ($post_id !== null) {
            // 特定の投稿IDについて参加を確認
            $sql = $pdo->prepare('SELECT * FROM participation WHERE user_id = ? AND post_id = ?');
            $sql->execute([$user_id, $post_id]);
            $participation = $sql->fetch(PDO::FETCH_ASSOC);
          } else {
            // 投稿IDが指定されていない場合は、参加している全ての投稿を確認
            $sql = $pdo->prepare('SELECT * FROM participation WHERE user_id = ?');
            $sql->execute([$user_id]);
            $participation = $sql->fetch(PDO::FETCH_ASSOC);
          }

          // 参加していれば評価するリンクを表示
          if ($participation) {
            echo '<span class="hyouka"><button onclick="location.href=\'hyouka.php?post_id=' . ($post_id ? $post_id : $participation['post_id']) . '\'">評価する</button></span>';
          } else {
              echo '<span class="hyouka">評価する: 参加した投稿がありません</span>';
          }
          echo '</div><hr>';
          echo '<div id="profile_info_1">';
          echo '<br><span class="title">自己紹介</span><br>';
          $self_intro = isset($row['bio']) ? nl2br(htmlspecialchars($row['bio'], ENT_QUOTES, 'UTF-8')) : '自己紹介はまだ登録されていません。';
          echo '<br><p>' . $self_intro . '</p><br>';
          echo '<hr></div>';
          echo '<div id="profile_info_2">';
          echo '<br><span class="age_sub">年齢:</span>';
          echo '<span class="age_main">' . htmlspecialchars($row['age'], ENT_QUOTES, 'UTF-8') . '</span><br>';
          echo '<br><span class="sex_sub">性別:</span>';
          $sex = isset($row['gender']) ? htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8') : '未設定';
          echo '<span class="sex_main">' . $sex . '</span><br><br>';
          echo '<span class="region_sub">活動地域:</span>';
          echo '<span class="region_main">' . htmlspecialchars($row['activity_region'], ENT_QUOTES, 'UTF-8') . '</span><br><br>';
          echo '<hr></div>';
          echo '<div id="profile_info_3">';
          echo '<br><span class="title">好きなスポーツ</span><br><br>';
 
          // user_sportsテーブルからスポーツ情報を取得
           $sport_sql = $pdo->prepare('SELECT s.sport_name, us.level FROM user_sport us JOIN sport s ON us.sport_id = s.sport_id WHERE us.user_id = ?');
            $sport_sql->execute([$user_id]);
             $user_sport = $sport_sql->fetchAll(PDO::FETCH_ASSOC);
              foreach ($user_sport as $sport) {
                $sport_name = isset($sport['sport_name']) ? htmlspecialchars($sport['sport_name'], ENT_QUOTES, 'UTF-8') : 'スポーツ名不明';
                 $level = isset($sport['level']) ? htmlspecialchars($sport['level'], ENT_QUOTES, 'UTF-8') : null;
                  if ($level !== null && $level !== '未設定') {
                     echo '<div class=sport_name>' . $sport_name . ' - ' . $level . '</div>';
                  }else {
                    echo '<div class=sport_name>' . $sport_name . '</div>';
                  }
                }
 
          echo '</div>';
        }
      } catch (PDOException $e) {
        echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
      }
      ?>
    </div><!--/main-->
  </div><!--/contents-->
 
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
 
 