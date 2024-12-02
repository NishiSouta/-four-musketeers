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
</head>
<body>
<div id="container">
  <header>
    <h1 id="logo"><a href="index.php"><img src="images/LS.png" alt="Link Sports"></a></h1>

    </header>

    <?php
$backURL = $_SERVER['HTTP_REFERER']; // 前のページのURLを取得
?>

<nav id="batu">
  <!-- 戻るボタン -->
  <a href="<?php echo $backURL; ?>" class="nav-button">
    <img src="images/batu.png" alt="×（バツ）">
  </a>
</nav>

  <!-- ケバブメニュー -->
  <div class="kebab-icon">
  <span class="dli-more-v"></span>
    <div id="menu-items" class="hidden">
      <a href="option1.php">オプション1</a>
      <a href="option2.php">オプション2</a>
      <a href="option3.php">オプション3</a>
    </div>
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
          echo htmlspecialchars(date('Y年n月j日（D） H:i', strtotime($post['event_datetime_from'])), ENT_QUOTES, 'UTF-8') . "～" . 
               htmlspecialchars(date('H:i', strtotime($post['event_datetime_to'])), ENT_QUOTES, 'UTF-8');
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
    <?php
    // PDO接続
    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("データベース接続エラー: " . $e->getMessage());
    }

    // メッセージを取得
    $sql = "SELECT message.message_text, user.user_name 
            FROM message 
            INNER JOIN user 
            ON message.user_id = user.user_id 
            ORDER BY message.message_id";

    try {
        $stmt = $pdo->query($sql);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($messages) > 0) {
            foreach ($messages as $row) {
                echo "<p><strong>" . htmlspecialchars($row['user_name']) . ":</strong> " . htmlspecialchars($row['message_text']) . "</p>";
            }
        } else {
            echo "<p>メッセージがありません</p>";
        }
    } catch (PDOException $e) {
        die("クエリ実行エラー: " . $e->getMessage());
    }
    ?>
</div>



<form action="send_message.php" method="post" class="chat-form">
    <label for="message">メッセージを入力してください:</label>
    <input type="text" id="message" name="message" required>
    <!-- chat_idを隠しフィールドで送信 -->
    <?php
$sql = $pdo->prepare('SELECT chat_id FROM chat WHERE post_id = ?');
$sql->execute([$post_id]);
$chat = $sql->fetch(PDO::FETCH_ASSOC);
$chat_id = $chat['chat_id'] ?? null;
?>
    <input type="hidden" name="chat_id" value="<?php echo htmlspecialchars($chat_id, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit">送信</button>
</form>

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
</div>
<script>
document.getElementById('kebab-icon').addEventListener('click', function() {
  const menu = document.getElementById('menu-items');
  menu.classList.toggle('hidden');
});
</script>
</body>
</html>
