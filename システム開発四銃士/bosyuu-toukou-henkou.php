<?php
session_start();
require 'db-connect.php';

// 必要なパラメータが渡されているか確認
if (!isset($_GET['post_id'])) {
    echo "投稿IDが指定されていません。";
    exit;
}

$post_id = $_GET['post_id'];

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 指定された投稿IDのデータを取得
    $sql = $pdo->prepare('SELECT * FROM post WHERE post_id = ?');
    $sql->execute([$post_id]);
    $post = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "指定された投稿が見つかりません。";
        exit;
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集投稿変更画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style-bosyuu-touroku.css">
  <script src="js/openclose.js"></script>
  <script src="js/fixmenu_pagetop.js"></script>
</head>
<body>
<div id="container">
<header>
    <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Link Sports"></a></h1>
    <nav id="batu">
        <a href="toukou-itiran.php"><img src="images/batu.png" alt="×（バツ）"></a>
    </nav>
</header>

<div id="contents">
  <div id="main">
    <section>
      <h2>投稿編集</h2>  
      <form action="bosyuu-henkou-kannryou.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="form-group">
          <label for="bosyuutaitoru">募集タイトル</label>
          <input type="text" id="bosyuutaitoru" name="bosyuutaitoru" value="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="event_datetime_from">開催日時</label><br>
          <input id="event_datetime_from" name="event_datetime_from" type="datetime-local" value="<?php echo date('Y-m-d\TH:i', strtotime($post['event_datetime_from'])); ?>" required> から<br>
          <input id="event_datetime_to" name="event_datetime_to" type="datetime-local" value="<?php echo date('Y-m-d\TH:i', strtotime($post['event_datetime_to'])); ?>" required> まで
        </div>

        <div class="form-group">
          <label for="recruit_number">募集する人数</label>
          <select id="recruit_number" name="recruit_number">
            <?php for ($i = 1; $i <= 10; $i++) {
                $selected = ($i == $post['recruit_number']) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            } ?>
          </select>
        </div>

        <div class="form-group">
          <label for="current_number">すでに集まっている人数</label>
          <select id="current_number" name="current_number">
            <?php for ($i = 0; $i <= 10; $i++) {
                $selected = ($i == $post['current_number']) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            } ?>
          </select>
        </div>

        <div class="form-group">
          <label for="location">実施場所</label>
          <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($post['location'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="participation_fee">参加費</label>
          <select id="participation_fee" name="participation_fee">
            <option value="0" <?php echo ($post['participation_fee'] == '0') ? 'selected' : ''; ?>>無料</option>
            <?php for ($i = 1000; $i <= 30000; $i += 1000) {
                $selected = ($i == $post['participation_fee']) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>{$i}円以内</option>";
            } ?>
          </select>
        </div>

        <div class="form-group">
          <label for="syosinsya">
            <input type="checkbox" id="syosinsya" name="syosinsya" value="ok" <?php echo ($post['syosinsya'] == 'ok') ? 'checked' : ''; ?>> 初心者OK
          </label>
        </div>

        <div class="form-group">
          <label for="description">その他</label>
          <textarea id="description" name="description"><?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div id="center">
          <input type="submit" value="変更する" id="button">
        </div>
      </form>
    </section>
  </div>
</div>

<footer>
    <small>&copy; Link Sports All Rights Reserved.</small>
</footer>
</div>

<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>
<div id="menubar_hdr" class="close"></div>
</body>
</html>
