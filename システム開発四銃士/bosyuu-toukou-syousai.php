<?php
session_start();
require 'db-connect.php';
$user_id = $_SESSION['user_id'];

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


// URLパラメータからスポーツ名とスポーツ画像を取得
$sportName = isset($_GET['sport_name']) ? htmlspecialchars($_GET['sport_name'], ENT_QUOTES, 'UTF-8') : 'スポーツ名不明';
$sportImg = isset($_GET['sport_img']) ? htmlspecialchars($_GET['sport_img'], ENT_QUOTES, 'UTF-8') : 'default_sport.jpg';
$sportIcon = isset($_GET['sport_icon']) ? htmlspecialchars($_GET['sport_icon'], ENT_QUOTES, 'UTF-8') : 'default_sport.jpg';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>募集投稿詳細画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style-bosyuu-toukou-syousai.css"> <!-- デザインに合うCSSを指定 -->
  <script src="js/openclose.js"></script>
</head>
<body>
<div id="container">
  <header>
    <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Link Sports"></a></h1>

    </header>

    <?php
$backURL = $_SERVER['HTTP_REFERER']; // 前のページのURLを取得
?>

<div id="floatLR">
  <nav id="batu">
    <!-- Back Button -->
    <a href="toukou-itiran.php" class="nav-button">
      <img src="images/batu.png" alt="×（バツ）">
    </a>
  </nav>

<?php
$sql = "SELECT user_id FROM post WHERE post_id = :post_id";
$stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $author_id = $stmt->fetchColumn();

if ( $user_id=== $author_id) {
?>

  <nav id="menubar-s" class="open">
    <ul>
      <li><a href="bosyuu-toukou-henkou.php?post_id=<?php echo htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8'); ?>">編集する</a>
      <li><a href="toukou-delete.php?post_id=<?php echo htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8'); ?>">削除</a></li>
    </ul>
  </nav>

<?php
} // End of condition
?>
</div>

  <div id="content">  
    <section class="post-details">
    <div class="image-banner">
      <img src="images/<?php echo $sportImg; ?>" alt="<?php echo $sportName; ?>バナー">
    </div>
    <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <p class="category">
    <img src="images/<?php echo $sportIcon; ?>" alt="<?php echo $sportName; ?>アイコン">
    <?php echo $sportName; ?></p>
    <hr>
    <div class="detail-item">
        <strong>開催日時</strong><br>
        <?php 
        $eventDatetimeFrom = new DateTime($post['event_datetime_from']);
        $eventDatetimeTo = new DateTime($post['event_datetime_to']);
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];

        echo htmlspecialchars($eventDatetimeFrom->format('Y年n月j日'), ENT_QUOTES, 'UTF-8') . 
             "（" . $weekdays[$eventDatetimeFrom->format('w')] . "） " . 
             htmlspecialchars($eventDatetimeFrom->format('H:i'), ENT_QUOTES, 'UTF-8') . "～" . 
             htmlspecialchars($eventDatetimeTo->format('H:i'), ENT_QUOTES, 'UTF-8');
    ?>
    </div>
    <div class="detail-item">
        <strong>場所</strong><br>
        <?php echo htmlspecialchars($post['location'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <div class="detail-item">
        <strong>参加費</strong><br>
        <?php echo ($post['participation_fee'] == 0) ? "無料" : htmlspecialchars($post['participation_fee'], ENT_QUOTES, 'UTF-8') . "円以内"; ?>
    </div>
    <div class="detail-item">
        <strong>募集人数</strong><br> 
        <?php echo htmlspecialchars($post['recruit_number'], ENT_QUOTES, 'UTF-8'); ?>人
    </div>
    <div class="detail-item">
        <strong>すでに集まっている人数</strong><br>
        <?php echo htmlspecialchars($post['current_number'], ENT_QUOTES, 'UTF-8'); ?>人
    </div>
    <div class="detail-item">
        <strong>その他</strong><br>
        <?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <hr>
    <div class="chat-box">
      <div class="detail-item">
        <strong>メッセージ</strong>
      </div>
    <?php
    // PDO接続
    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("データベース接続エラー: " . $e->getMessage());
    }

    $sql = $pdo->prepare('SELECT chat_id FROM chat WHERE post_id = ?');
    $sql->execute([$post_id]);
    $chat = $sql->fetch(PDO::FETCH_ASSOC);
    $chat_id = $chat['chat_id'] ?? null;
    
  // メッセージを取得（chat_idでフィルタリング）
  $sql = "SELECT message.message_text, user.user_name, user.profile_image, user.user_id, message.sent_at
        FROM message 
        INNER JOIN user 
        ON message.user_id = user.user_id 
        WHERE message.chat_id = ? 
        ORDER BY message.message_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$chat_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($messages) > 0) {
  foreach ($messages as $row) {
      $userId = htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8');
      $userImage = htmlspecialchars($row['profile_image'], ENT_QUOTES, 'UTF-8');
      $userName = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
      $messageText = htmlspecialchars($row['message_text'], ENT_QUOTES, 'UTF-8');
      $createdAt = htmlspecialchars(date('n月j日 H:i', strtotime($row['sent_at'])), ENT_QUOTES, 'UTF-8');

      // 投稿者かどうかの確認
      $isAuthor = ($userId == $author_id);

      echo "<div class='message-item'>";
      echo "  <a href='myprofile-user.php?user_id=$userId'>";
      echo "      <img src='uploads/$userImage' alt='$userName' class='user-image'>";
      echo "  </a>";
      echo "  <div class='message-content'>";
      echo "      <p class='user-name'>";
      echo $userName;
      // 投稿者であれば「募集者」ラベルを追加
      if ($isAuthor) {
          echo " <span class='author-label'>募集者</span>";
      }
      echo "      <span class='message-timestamp'>$createdAt</span>";
      echo "      </p>";
      echo "      <p class='message-text'>$messageText</p>";
      echo "  </div>";
      echo "</div>";
  }
} else {
  echo "<p>メッセージがありません</p>";
}



    ?>
</div>

<form action="send_message.php" method="post" class="chat-form">
    <div class="chat-input-container">
        <input type="text" id="message" name="message" required placeholder="メッセージを入力">
        <button type="submit">送信</button>
    </div>
    <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat_id, ENT_QUOTES, 'UTF-8'); ?>">
</form>
<br>

<?php
// 参加状態の確認
$participation_sql = $pdo->prepare('SELECT COUNT(*) FROM participation WHERE post_id = ? AND user_id = ?');
$participation_sql->execute([$post_id, $user_id]);
$is_participated = $participation_sql->fetchColumn() > 0;

// 募集者であるかの確認
$is_author = ($user_id == $author_id);
?>

<?php if (!$is_author): // 募集者でない場合のみ表示 ?>
  <!-- 参加・キャンセルの切り替え -->
  <form action="<?php echo $is_participated ? 'cancel-participation.php' : 'participate.php'; ?>" method="post" class="participate-form">
      <input type="hidden" id="button" name="post_id" value="<?php echo htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" id="button" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
      <button type="submit" id="button">
          <?php echo $is_participated ? 'キャンセルする' : '参加する'; ?>
      </button>
  </form>
<?php endif; ?>

</div>
<script>
    // チャットボックスが常に下にスクロールされるようにする関数
    window.onload = () => {
        let chatBox = document.querySelector(".chat-box");
        chatBox.scrollTop = chatBox.scrollHeight;
    };
</script>
</section>
</div>

  <footer>
    <small>&copy; Link Sports All Rights Reserved.</small>
  </footer>
  <!--
<script>
document.getElementById('kebab-icon').addEventListener('click', function() {
  const menu = document.getElementById('menu-items');
  menu.classList.toggle('hidden');
});
</script>
  -->

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
