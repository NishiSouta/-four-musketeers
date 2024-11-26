<?php session_start();
require 'db-connect.php';
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 募集件数を取得
    $stmt = $pdo->query('
        SELECT 
            s.sport_id, 
            s.sport_name, 
            COUNT(p.post_id) AS count 
        FROM 
            sport s 
        LEFT JOIN 
            post p 
        ON 
            s.sport_id = p.sport_id 
        GROUP BY 
            s.sport_id, s.sport_name
    ');
$sports = [];
foreach ($stmt as $row) {
    $sports[] = [
            'sport_id' => $row['sport_id'],
            'sport_name' => $row['sport_name'],
            'count' => $row['count']
    ];
    }
} catch (PDOException $e) {
    echo '<p>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    exit;
}
$sports_data = [];
foreach ($sports as $sport) {
    $sports_data[$sport['sport_name']] = $sport['count'];
}

// 募集件数を取得する関数を用意（キーが見つからない場合は 0 を返す）
function get_sport_count($sports_data, $sport_name) {
    return isset($sports_data[$sport_name]) ? $sports_data[$sport_name] : 0;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>投稿募集一覧画面</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/link.css">
<script src="js/openclose.js"></script>
<script src="js/fixmenu_pagetop.js"></script>
</head>

<body>

<div id="container">

<?php require 'header.php'; ?>

<div id="contents">
<div id="main">
<section>

<h2>募集中のスポーツ</h2>

<table class="ta1">
	<tr>
	  <th><a href="toukou-syousai.php?sport=野球"><img src="images/baseball.jpg" alt=""><br>野球<br><?= get_sport_count($sports_data, '野球'); ?> 件</a></th>
	  <th><a href="toukou-syousai.php?sport=ジョギング"><img src="images/run.jpg" alt=""><br>ジョギング<br><?= get_sport_count($sports_data, 'ジョギング'); ?> 件</a></th>
	</tr>
	<tr>
	  <th><a href="toukou-syousai.php?sport=テニス"><img src="images/tennis.jpg" alt=""><br>テニス<br><?= get_sport_count($sports_data, 'テニス'); ?> 件</a></th>
	
	  <th><a href="toukou-syousai.php?sport=バレーボール"><img src="images/volleyball.jpg" alt=""><br>バレーボール<br><?= get_sport_count($sports_data, 'バレーボール'); ?> 件</a></th>
	</tr>
	<tr>
	  <th><a href="toukou-syousai.php?sport=サッカー"><img src="images/soccer.jpg" alt=""><br>サッカー<br><?= get_sport_count($sports_data, 'サッカー'); ?> 件</a></th>
	
	  <th><a href="toukou-syousai.php?sport=バスケットボール"><img src="images/basketball.jpg" alt=""><br>バスケットボール<br><?= get_sport_count($sports_data, 'バスケットボール'); ?> 件</a></th>
	</tr>
	<tr>
	  <th><a href="toukou-syousai.php?sport=卓球"><img src="images/table_tennis.jpg" alt=""><br>卓球<br><?= get_sport_count($sports_data, '卓球'); ?> 件</a></th>
	
	  <th><a href="toukou-syousai.php?sport=バドミントン"><img src="images/badminton.jpg" alt=""><br>バドミントン<br><?= get_sport_count($sports_data, 'バドミントン'); ?> 件</a></th>
	</tr>
	<tr>
	  <th><a href="toukou-syousai.php?sport=筋トレ"><img src="images/gym.jpg" alt=""><br>筋トレ<br><?= get_sport_count($sports_data, '筋トレ'); ?> 件</a></th>
	
	  <th><a href="toukou-syousai.php?sport=ボクシング"><img src="images/boxing.jpg" alt=""><br>ボクシング<br><?= get_sport_count($sports_data, 'ボクシング'); ?> 件</a></th>
	</tr>
	<tr>
	  <th><a href="toukou-syousai.php?sport=ゴルフ"><img src="images/golf.jpg" alt=""><br>ゴルフ<br><?= get_sport_count($sports_data, 'ゴルフ'); ?> 件</a></th>
	
	  <th><a href="toukou-syousai.php?sport=アメリカンフットボール"><img src="images/american_football.jpg" alt=""><br>アメリカンフットボール<br><?= get_sport_count($sports_data, 'アメリカンフットボール'); ?> 件</a></th>
	</tr>
  </table>

</section>

</div>

</div>
</div>

<footer>
<small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>
<span class="pr"><a href="https://template-party.com/" target="_blank">《Web Design:Template-Party》</a></span>
</footer>

<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>

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
