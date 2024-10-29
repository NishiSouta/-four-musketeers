
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>募集入力画面</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="css/style-bosyuu-touroku.css">
</head>

<?php
echo '<div id="container">';
echo '
	<header>
		<h1 id="logo"><a href="index.html"><img src="images/LS.png" alt="Photo Gallery"></a></h1>
		<aside id="header-img"><a href="login.php"><img src="images/account_circle.png" alt=""></a></aside>
	</header>

    <nav id="batu">
    <a href="link.php"><img src="images/batu.png" alt="×（バツ）"></a>
	</nav>';
echo '<div id="contents">';
echo '<div id="main">';
echo '<section>';
    
echo '<h2>';
     
    // URLパラメータからスポーツ名を取得して表示
    if (isset($_GET['sport'])) {
        echo htmlspecialchars($_GET['sport'], ENT_QUOTES, 'UTF-8');
    } else {
        echo 'スポーツ名が指定されていません';
    }
    
echo '</h2>';



echo '<form action="bosyuu_touroku_kannryuou.php" method="post">';//form

echo '募集タイトル<br>';	//募集タイトル
echo '<input type="text" name="bosyuutaitoru"><br>';//募集タイトル入力テキスト

echo '開催日時<br>';//開催日時
echo '<input name="date" type="date" />から<br>';//開催日時から
echo '<input name="date" type="date" />まで<br>';//開催日時まで

echo '条件<br>';//募集条件

echo '募集する人数';//募集する人数
echo '<select name="ninzuu">';//募集する人数(あとで変数名変える)
echo '<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>';
echo '</select><br>';

echo 'すでに集まっている人数<select name="ninzuu">';//すでに集まっている人数(あとで変数名変える)
echo '<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>';
echo '</select><br>';

echo '実施場所<br>';//実施場所
echo '<input type="text" name="zissibasyo"><br>';//実施場所(あとで変数名変える)

echo '参加費<br>';//参加費
echo '<select name="sum">
<option value="0">無料</option>
<option value="500">500円以内</option>
<option value="1000">1000円以内</option>
<option value="2000">2000円以内</option>
<option value="3000">3000円以内</option>
<option value="4000">4000円以内</option>
<option value="5000">5000円以内</option>
<option value="6000">6000円以内</option>
<option value="7000">7000円以内</option>
<option value="8000">8000円以内</option>
<option value="9000">9000円以内</option>
<option value="10000">10000円以内</option>
<option value="15000">15000円以内</option>
<option value="20000">20000円以内</option>
<option value="25000">25000円以内</option>
<option value="30000">30000円以内</option>';
echo '</select><br>';

echo '<input type="checkbox" id="syosinsya" name="syosinsya" />初心者OK<br>';//チェックボックス 初心者OK

echo 'その他<br>';
echo '<input type="text" name="sonota">';//その他(あとで変数名変える)

echo '<div id=center>';
echo '<p><input type="button" value="募集する" id="button"></p>';//募集ボタン
echo '</div>';

echo '</form>'; //fomr 終わり


echo '</section>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<footer>';
echo '<small>Copyright&copy; <a href="index.html">Photo Gallery</a> All Rights Reserved.</small>';
echo '</footer>'; 

