<?php
// セッションを開始
session_start();

// すべてのセッション変数を解除
$_SESSION = array();

// セッションIDを保持しているクッキーが存在する場合は、それを無効化
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを破棄
session_destroy();

// ログアウト後のリダイレクト
header("Location: login.php");
exit;
?>