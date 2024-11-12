<?php
// db-connect.php を読み込む
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);
// フォームが送信された場合に処理を実行
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ユーザーが送信したデータを取得
    $userName = $_POST['user_name'];
    $eMail = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $activityRegion = $_POST['activity_region'];
    $sportId = $_POST['sport_id'];

    // パスワードをハッシュ化（セキュリティのため）
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $profileImage = null;
if (isset($_FILES['user_icon']) && $_FILES['user_icon']['error'] === UPLOAD_ERR_OK) {
    // アップロードされたファイルの名前を取得
    $fileName = $_FILES['user_icon']['name'];
    // 保存先のディレクトリを指定
    $uploadDir = 'uploads/';
    // ファイルパスを指定
    $filePath = $uploadDir . basename($fileName);

    // ファイルを指定した場所に移動
    if (move_uploaded_file($_FILES['user_icon']['tmp_name'], $filePath)) {
        // 成功した場合はファイル名をデータベースに保存
        $profileImage = $fileName;
    } else {
        echo "画像のアップロードに失敗しました。";
    }
}

    try {
        // データベースに接続
        $pdo = new PDO($connect, USER, PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // データベースにユーザー情報を挿入
        $sql = "INSERT INTO user (user_name, email, password, gender, age, activity_region,profile_image)
                VALUES (:user_name, :email, :password, :gender, :age, :activity_region, :profile_image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_name', $userName);
        $stmt->bindParam(':email', $eMail);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':activity_region', $activityRegion);
        $stmt->bindParam(':profile_image', $profileImage);

        // クエリ実行
        $stmt->execute();

        $userId = $pdo->lastInsertId();

        // user_sportテーブルに選択されたスポーツを追加
        $sql = "INSERT INTO user_sport (user_id, sport_id) VALUES (:user_id, :sport_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':sport_id', $sportId);
        $stmt->execute();
       
        if (isset($sportId)) {     echo '$sportIdは存在します'; } else {     echo '$sportIdは存在しません'; }
        // 登録成功後、index.html に遷移
        header("Location: index.html");
        exit;
    } catch (PDOException $e) {
        // エラーメッセージの表示（デバッグ用）
        echo "データベースエラー: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規アカウント登録</title>
    <link rel="stylesheet" href="css/sinki.css">
</head>
<body>
<div id="login-container">
<header>
        <h1 id="logo">
            <a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a>
        </h1>
        <aside id="header-img"><div id="close-btn"></div></aside>
    </header>

    <h1>新規アカウント登録</h1>
    <!-- フォーム部分 -->
    <form method="POST" action="sinki-touroku.php" enctype="multipart/form-data">
        <!-- プロフィール画像選択フォーム -->
        <div id="center">
            <div class="user-icon">
                <label for="user_icon_input">
                <img src="images/default_icon.png" id="user_icon_preview" class="rounded-icon">
                </label>
                <input type="file" id="user_icon_input" name="user_icon" accept="image/*" style="display: none;" onchange="previewUserIcon(event)">
            </div>
        </div>
        <input type="text" name="user_name" class="input-field" placeholder="ユーザー名" required>
        <input type="email" name="email" class="input-field" placeholder="メールアドレス" required>
        <input type="password" name="password" class="input-field" placeholder="パスワード" required>

        

        <!-- 性別プルダウンメニュー -->
        <select name="gender" class="input-field" required>
            <option value="" disabled selected>性別</option>
            <option value="男性">男性</option>
            <option value="女性">女性</option>
            <option value="その他">その他</option>
        </select>

        <!-- 年齢プルダウンメニュー -->
        <select name="age" class="input-field" required>
            <option value="" disabled selected>年齢</option>
            <?php
            // 18歳から100歳までの選択肢を生成
            for ($i = 18; $i <= 100; $i++) {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
            ?>
        </select>

        <!-- 活動地域プルダウンメニュー -->
        <select name="activity_region" class="input-field" required>
            <option value="" disabled selected>活動地域</option>
            <option value="北海道">北海道</option>
            <option value="青森県">青森県</option>
            <option value="岩手県">岩手県</option>
            <option value="宮城県">宮城県</option>
            <option value="秋田県">秋田県</option>
            <option value="山形県">山形県</option>
            <option value="福島県">福島県</option>
            <option value="茨城県">茨城県</option>
            <option value="栃木県">栃木県</option>
            <option value="群馬県">群馬県</option>
            <option value="埼玉県">埼玉県</option>
            <option value="千葉県">千葉県</option>
            <option value="東京都">東京都</option>
            <option value="神奈川県">神奈川県</option>
            <option value="新潟県">新潟県</option>
            <option value="富山県">富山県</option>
            <option value="石川県">石川県</option>
            <option value="福井県">福井県</option>
            <option value="山梨県">山梨県</option>
            <option value="長野県">長野県</option>
            <option value="岐阜県">岐阜県</option>
            <option value="静岡県">静岡県</option>
            <option value="愛知県">愛知県</option>
            <option value="三重県">三重県</option>
            <option value="滋賀県">滋賀県</option>
            <option value="京都府">京都府</option>
            <option value="大阪府">大阪府</option>
            <option value="兵庫県">兵庫県</option>
            <option value="奈良県">奈良県</option>
            <option value="和歌山県">和歌山県</option>
            <option value="鳥取県">鳥取県</option>
            <option value="島根県">島根県</option>
            <option value="岡山県">岡山県</option>
            <option value="広島県">広島県</option>
            <option value="山口県">山口県</option>
            <option value="徳島県">徳島県</option>
            <option value="香川県">香川県</option>
            <option value="愛媛県">愛媛県</option>
            <option value="高知県">高知県</option>
            <option value="福岡県">福岡県</option>
            <option value="佐賀県">佐賀県</option>
            <option value="長崎県">長崎県</option>
            <option value="熊本県">熊本県</option>
            <option value="大分県">大分県</option>
            <option value="宮崎県">宮崎県</option>
            <option value="鹿児島県">鹿児島県</option>
            <option value="沖縄県">沖縄県</option>
        </select>

        <!-- 興味のあるスポーツプルダウンメニュー -->
        <select name="sport_id" class="input-field" required>
            <option value="" disabled selected>興味のあるスポーツ</option>
            <option value="1">野球</option>
            <option value="2">ジョギング</option>
            <option value="3">テニス</option>
            <option value="4">サッカー</option>
            <option value="5">バスケットボール</option>
            <option value="6">卓球</option>
            <option value="7">バドミントン</option>
            <option value="8">筋トレ</option>
            <option value="9">ボクシング</option>
            <option value="10">ゴルフ</option>
            <option value="11">アメリカンフットボール</option>
            <option value="12">バレーボール</option>   
        </select>
        <button type="submit" class="btn-login">登録する</button>
    </form>
</div>
<script>
    // プロフィール画像のプレビュー表示
    function previewUserIcon(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('user_icon_preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // ×ボタンを押した時の処理
    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'index.html'; // 遷移先のURLを指定
    });
</script>
</body>
</html>
