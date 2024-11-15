<?php
session_start(); // セッションを開始
require 'db-connect.php'; // db-connect.php を読み込む

$error_message = ''; // エラーメッセージを初期化

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTで送信されたメールアドレスとパスワードを取得
    $eMail = $_POST['email']; // 'user_mail' から 'email' に変更
    $password = $_POST['password'];

    if (empty($eMail) || empty($password)) {
        $error_message = 'メールアドレスとパスワードを入力してください';
    } else {
        try {
            $pdo = new PDO($connect, USER, PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            // ユーザーをデータベースで検索
            $sql = 'SELECT * FROM user WHERE email = :email';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $eMail); // 'user_mail' から 'email' に変更
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // パスワードが一致するかチェック
            if ($user && password_verify($password, $user['password'])) {
                // パスワードが一致する場合、セッションにユーザー情報を保存
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                
                header('Location: index.php');
                exit();
            } else {
                $error_message = 'メールアドレスまたはパスワードが違います';
            }
            
        } catch (PDOException $e) {
            $error_message = 'データベースエラー: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>

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

<h1>ログイン</h1>

<form method="POST" action="login.php">
    <input type="text" name="email" class="input-field" placeholder="メールアドレス" required> <!-- name="email" に変更 -->
    <input type="password" name="password" class="input-field" placeholder="パスワード" required>

    <button type="submit" class="btn-login">ログイン</button>
    <div id="login-container">
        <a href="sinki-touroku.php" class="sinki-link">新規アカウント登録</a>
    </div>

    <?php
    if (!empty($error_message)) {
        echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>
</form>

<footer>
    <small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
    <span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
</footer>
</div>

<script>
    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'index.php'; // 遷移先のURLを指定
    });
</script>

</body>
</html>