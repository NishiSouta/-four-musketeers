<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ログインページ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="login.css"> <!-- login.css をリンク -->
</head>

<body>

<div id="container">

    <!-- ヘッダー -->
    <header>
        <h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
    </header>

    <div id="login-container">
        <!-- ×ボタン -->
        <div id="close-btn"></div>

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

    <!-- フッター -->
    <footer>
        <small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
        <span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
    </footer>

</div>

<script>
    // ×ボタンを押した時の処理
    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'index.html'; // 遷移先のURLを指定
    });
</script>

</body>
</html>
