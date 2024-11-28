<?php
session_start();
require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集詳細画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ここにサイト説明を入れます">
  <meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
  <link rel="stylesheet" href="css/style-toukou-syousai.css">
</head>
<body>
  <div id="container">
    <header>
      <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
      <aside id="header-img">
<?php
if (isset($_SESSION['user_id'])) {
    // ログインしている場合
    $user_id = $_SESSION['user_id'];
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'DELETE FROM post WHERE event_datetime_to < NOW() - INTERVAL 24 HOUR';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    $profile_img = isset($user['profile_image']) ? 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
    echo '<img alt="image" src="' . $profile_img . '" class="avatar">';
} else {
    // ログインしていない場合
    echo '<aside id="header-img"><a href="login.php"><img src="images/account_circle.png" alt=""></a></aside>';
}
?>
      </aside>
    </header>
    <nav id="batu">
      <a href="toukou-itiran.php"><img src="images/batu.png" alt="×（バツ）"></a>
    </nav>
    <div id="contents">
      <div id="main">
        <section>
          <h2>
<?php
// URLパラメータからスポーツ名を取得して表示
if (isset($_GET['sport'])) {
    $sport = htmlspecialchars($_GET['sport'], ENT_QUOTES, 'UTF-8');
    echo $sport;
} else {
    echo 'スポーツ名が指定されていません';
    exit;
}
?>
</h2>
<div id="details">
<?php
try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // スポーツ名を取得
    if (isset($_GET['sport'])) {
        $sportName = htmlspecialchars($_GET['sport'], ENT_QUOTES, 'UTF-8');

        // クエリ: post, sport, user テーブルを結合して必要な情報を取得
          $sql = $pdo->prepare(
            'SELECT
                p.post_id, 
                u.profile_image, 
                u.user_name,
                p.title, 
                p.recruit_number, 
                p.current_number, 
                p.location, 
                p.syosinsya, 
                p.participation_fee, 
                s.sport_icon, 
                s.sport_img 
             FROM 
                post p 
             JOIN 
                sport s 
             ON 
                p.sport_id = s.sport_id 
             JOIN 
                user u 
             ON 
                p.user_id = u.user_id 
             WHERE 
                s.sport_name = ?'
        );
        $sql->execute([$sportName]);

        // 結果を取得
        $posts = $sql->fetchAll(PDO::FETCH_ASSOC);

        // 結果を出力
        if ($posts) {
          foreach ($posts as $post) {
            $profileImage = isset($post['profile_image']) && $post['profile_image'] != '' 
                ? 'uploads/' . htmlspecialchars($post['profile_image'], ENT_QUOTES, 'UTF-8') 
                : 'images/default_profile.png';
        
            // 例: 募集詳細ページへのリンクを作成
            $link = 'bosyuu-toukou-syousai.php?post_id=' . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') .
            '&sport_name=' . urlencode($sportName) .
            '&sport_img=' . urlencode($post['sport_img']);
            '&sport_icon=' . urlencode($post['sport_icon']);
            echo '<a href="' . $link . '" class="post-row">';

            echo '<div class="post-left">';
            echo '<img src="' . $profileImage . '" alt="プロフィール画像" class="profile-image">';
            echo '<img src="images/' . htmlspecialchars($post['sport_icon'], ENT_QUOTES, 'UTF-8') . '" alt="スポーツアイコン" class="sport-icon">';
            echo '</div>';
        
            echo '<div class="post-center">';
            echo '<div class="info-row">';
            if ($post['syosinsya'] === 'ok') {
                echo '<span class="beginner-ok">初心者OK</span>';
            }
            if ($post['participation_fee'] == 0) {
                echo '<span class="free">無料</span>';
            }
            echo '</div>';
            echo '<h3>' . htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . '</h3>';
            echo '<p class="user-name">募集者: ' . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p><img src="images/icon.png" alt="人数アイコン" class="icon"> ' . 
                 htmlspecialchars($post['current_number'], ENT_QUOTES, 'UTF-8') . '名 / あと' . 
                 htmlspecialchars($post['recruit_number'], ENT_QUOTES, 'UTF-8') . '名</p>';
            echo '<p><img src="images/place.png" alt="場所アイコン" class="icon"> ' . 
                 htmlspecialchars($post['location'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '</div>';
        
            echo '<div class="post-right">';
            echo '<img src="images/' . htmlspecialchars($post['sport_img'], ENT_QUOTES, 'UTF-8') . '" alt="スポーツ画像" class="sport-img">';
            echo '</div>';
            echo '</a>'; // <a>タグを閉じる
        }
        
        } else {
            echo '<div id=zyouhou><p>該当する募集情報が見つかりません。</p></div>';
        }
    } else {
        echo '<p>スポーツ名が指定されていません。</p>';
    }
} catch (PDOException $e) {
    echo '<p>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}
?>
</div>

        </section>
      </div>
    </div>
    <footer>
      <small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
      <span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
    </footer>
  </div>
</body>
</html>
