<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザーリスト画面</title>
  <link rel="stylesheet" href="css/hyouka.css">
</head>
<body>
  <?php
    // ユーザーデータ（仮のデータとしてPHP配列で定義）
    $users = [
      ["name" => "ユーザー1", "icon" => "user1.png"],
      ["name" => "ユーザー2", "icon" => "user2.png"],
      ["name" => "ユーザー3", "icon" => "user3.png"],
    ];
  ?>

  <div class="container">
    <!-- アイコンとタイトル部分 -->
    <header>
    <h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
    <aside id="header-img"><div id="close-btn"></div></aside>
    </header>

    <!-- メッセージ -->
    <h1>このユーザーが良いと思ったらgoodボタンをお願いします</h1>>

    <!-- ユーザーリスト -->
    <div class="user-list">
      <?php foreach ($users as $user): ?>
        <div class="user">
          <img src="<?php echo $user['icon']; ?>" alt="<?php echo $user['name']; ?>" class="user-icon">
          <span class="user-name"><?php echo $user['name']; ?></span>
          <button class="like-button">👍</button>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- TOPページへボタン -->
    <button class="top-button">TOPページへ</button>
  </div>
</body>
</html>
