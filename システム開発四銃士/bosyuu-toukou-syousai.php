<?php
session_start();
require 'db-connect.php';

    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // スポーツ一覧取得
    $sports = [
        1 => '野球', 2 => 'ジョギング', 3 => 'テニス', 4 => 'サッカー',
        5 => 'バスケットボール', 6 => '卓球', 7 => 'バドミントン', 8 => '筋トレ',
        9 => 'ボクシング', 10 => 'ゴルフ', 11 => 'アメリカンフットボール', 12 => 'バレーボール'
    ];

    $sport_id = $_GET['sport'] ?? null;
    $sport_name = htmlspecialchars($sports[$sport_id] ?? '不明なスポーツ', ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募集投稿詳細画面</title>
    <link rel="stylesheet" href="css/style-bosyuu-toukou-syousai.css">
</head>
<body>
<div id="container">
    <header>
        <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="ロゴ"></a></h1>
        <aside id="header-img">
            <?php
            $sql = $pdo->prepare('SELECT profile_image FROM user WHERE user_id = ?');
            $sql->execute([$_SESSION['user_id']]);
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            $profile_img = isset($user['profile_image']) ? 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
            echo '<img src="' . $profile_img . '" class="avatar" alt="プロフィール画像">';
            ?>
        </aside>
        <nav id="batu">
            <a href="link.php"><img src="images/batu.png" alt="閉じる"></a>
        </nav>
    </header>

    <div id="contents">
        <div id="main">
            <section>
            <?php
      $user_id = $_SESSION['user_id'];
      try {
            $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $sql->execute([$user_id]);
        foreach ($sql as $row) {


          $profile_img = isset($row['profile_image']) ? 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : 'images/default_profile.png';
          echo '<img alt="image" src="' . $profile_img . '" class="avatar">';


          echo '</div>';
          echo '<div id="form-group">';
          echo '<span class="post">' . $row['title']. '</span>';
          echo '<span class="participation">開催日時:' . $row['event_datetime_from']. '</span><br>';
          echo '<span class="good_count">場所' . $row['good_count'] . '</span>';
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
                <!--<h2><?php echo $sport_name; ?></h2>
                <form action="bosyuu-touroku-kannryou.php?sport=<?php echo $sport_id; ?>" method="post">
                    <div class="form-group">
                        <label for="bosyuutaitoru">募集タイトル</label>
                        <input type="text" id="bosyuutaitoru" name="bosyuutaitoru" required>
                    </div>

                    <div class="form-group">
                        <label for="event_datetime_from">開催日時</label><br>
                        <input type="datetime-local" id="event_datetime_from" name="event_datetime_from" required> から<br>
                        <input type="datetime-local" id="event_datetime_to" name="event_datetime_to" required> まで
                    </div>

                    <div class="form-group">
                        <label for="recruit_number">募集する人数</label>
                        <select id="recruit_number" name="recruit_number">
                            <?php for ($i = 1; $i <= 10; $i++) echo "<option value=\"$i\">$i</option>"; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="current_number">すでに集まっている人数</label>
                        <select id="current_number" name="current_number">
                            <?php for ($i = 0; $i <= 10; $i++) echo "<option value=\"$i\">$i</option>"; ?>
                        </select>
                    </div>

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
                        <textarea id="description" name="description"></textarea>
                    </div>

                    <div id="center">
                        <input type="submit" value="参加する" id="button">
                    </div>
                </form>
            </section>
        </div>
    </div>-->

    <footer>
        <small>&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
    </footer>
</div>
</body>
</html>
