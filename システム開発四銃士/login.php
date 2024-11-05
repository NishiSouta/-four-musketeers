<?php
session_start(); // セッションを開始
// db-connect.php を読み込む
require 'db-connect.php';

// エラーメッセージを初期化
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTで送信されたメールアドレスとパスワードを取得
    $user_mail = $_POST['user_mail'];
    $password = $_POST['password'];

    // 入力が正しいかチェック
    if (empty($user_mail) || empty($password)) {
        // エラーメッセージをセット
        $error_message = 'メールアドレスとパスワードを入力してください';
    } else {
        // データベースに接続
        try {
            $pdo = new PDO($connect, USER, PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            // ユーザーをデータベースで検索
            $sql = 'SELECT * FROM user WHERE email = :user_mail';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_mail', $user_mail);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // パスワードが一致するかチェック
            if ($user && password_verify($password, $user['password'])) {
                // セッションを開始し、ログイン情報を保存
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_mail'] = $user['email'];

                header('Location: dashboard.php'); // ログイン成功後のリダイレクト先
                exit();
            } else {
                // ログイン失敗時
                $error_message = 'メールアドレスまたはパスワードが違います';
            }
        } catch (PDOException $e) {
            // データベース接続エラー処理
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

<!-- ログインフォーム -->
<form method="POST" action="login.php">
    <input type="text" name="user_mail" class="input-field" placeholder="メールアドレス" required>
    <input type="password" name="password" class="input-field" placeholder="パスワード" required>

    <button type="submit" class="btn-login">ログイン</button>
    <div id="login-container">
        <a href="sinki-touroku.php" class="sinki-link">新規アカウント登録</a>
    </div>

    <?php
    // エラーメッセージの表示
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
    // ×ボタンを押した時の処理
    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'index.html'; // 遷移先のURLを指定
    });
</script>

</body>
</html>
