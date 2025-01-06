<?php  
session_start();
require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ホーム画面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ここにサイト説明を入れます">
    <meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
    <link rel="stylesheet" href="css/hyouka.css">
    <script src="js/openclose.js"></script>
    <script src="js/fixmenu_pagetop.js"></script>
</head>

<body>

<div id="container">
<?php require 'header.php'; 

// post_idをGETパラメータから取得
if (!isset($_GET['post_id'])) {
    echo '投稿IDが指定されていません。';
    exit;
}
$post_id = $_GET['post_id'];

// 現在のユーザーIDをセッションから取得
$current_user_id = $_SESSION['user_id']; 

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // クエリ：post_idに紐づくユーザーを取得
    $sql = $pdo->prepare('
        SELECT DISTINCT 
            u.user_id, 
            u.user_name, 
            u.profile_image, 
            u.bio
        FROM 
            message m
        JOIN 
            user u ON m.user_id = u.user_id
        JOIN 
            chat c ON m.chat_id = c.chat_id
        WHERE 
            c.post_id = ?'
    );
    $sql->execute([$post_id]);
    
    // 結果を取得
    $participants = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    if ($participants) {
        echo '<div class="centered-container">';
        echo '<h2>このユーザーが良いと思ったら<br>goodボタンをお願いします</h2>';
        echo '<ul class="participant-list">';
        foreach ($participants as $participant) {
            // 自分自身を除外
            if ($participant['user_id'] == $current_user_id) {
                continue;
            }

            $profile_img = isset($participant['profile_image']) ? 'uploads/' . htmlspecialchars($participant['profile_image']) : 'images/default_profile.png';
            echo '<li class="participant-item" id="participant-' . $participant['user_id'] . '">';
            echo '<img alt="プロフィール画像" src="' . $profile_img . '" class="avatar">';
            echo '<div class="user-info">';
            echo '<strong>' . htmlspecialchars($participant['user_name'], ENT_QUOTES, 'UTF-8') . '</strong>';
            echo '<button 
                    class="thumbs-up-btn" 
                    id="thumbs-up-' . $participant['user_id'] . '"
                    data-user-id="' . $participant['user_id'] . '"
                    data-post-id="' . $post_id . '"
                    ' . (isset($_SESSION['evaluated_' . $post_id]) ? 'disabled' : '') . '>
                    <img src="' . (isset($_SESSION['evaluated_' . $post_id]) ? 'images/good_pressed.png' : 'images/good.png') . '" class="thumbs-up-img" alt="評価する">
                </button>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '参加者が見つかりません。';
    }
} catch (PDOException $e) {
    echo 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
<p><input type="button" value="TOPページへ" id="button" onclick="location.href='index.php'"></p>
</div><!-- /container -->

<footer>
<small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
<span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
</footer>

<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>

<!--メニュー開閉ボタン-->
<div id="menubar_hdr" class="close"></div>

<script>
if (OCwindowWidth() <= 900) {
    open_close("menubar_hdr", "menubar-s");
}

// 評価ボタンがクリックされたときの処理
document.querySelectorAll('.thumbs-up-btn').forEach(button => {
    button.addEventListener('click', function () {
        var userId = this.getAttribute('data-user-id');
        var postId = this.getAttribute('data-post-id');
        var buttonId = this.getAttribute('id');

        // ボタンがすでに評価済みの場合は何もしない
        if (this.disabled) return;

        // AJAXリクエストを送信
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'evaluate.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                // 成功した場合、ボタンを無効化して画像を変更
                if (xhr.responseText === '評価完了') {
                    document.getElementById(buttonId).disabled = true;
                    document.getElementById(buttonId).querySelector('img').src = 'images/good_pressed.png';
                }
            } else {
                console.error('AJAX request failed with status: ' + xhr.status);
            }
        };
        xhr.send('user_id=' + userId + '&post_id=' + postId);
    });
});

</script>

</body> 
</html>
