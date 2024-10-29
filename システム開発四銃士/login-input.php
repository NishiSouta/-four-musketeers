<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ログインページ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/login.css"> <!-- login.css をリンク -->
</head>

<body>

<div id="login-container">
<header>
<h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
<aside id="header-img"><div id="close-btn"></div></aside>
</header>
    <!-- ×ボタン -->
    

    <h2>ログイン</h2>

    <!-- ログインフォーム -->
    <form method="POST" action="login.php">
        <input type="text" name="email" class="input-field" placeholder="メールアドレス" required>
        <input type="password" name="password" class="input-field" placeholder="パスワード" required>

        <button type="submit" class="btn-login">ログイン</button>

        <a href="register.php" class="register-link">新規アカウント登録</a>

        <?php
        // エラーメッセージの表示
        if (isset($_GET['error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>
    </form>
</div>

<script>
    // ×ボタンを押した時の処理
    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'index.html'; // 遷移先のURLを指定
    });
</script>

</body>
</html>
