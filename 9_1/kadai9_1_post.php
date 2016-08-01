<?php

// セッションを開始する
session_start();
// トークンを発行する
$token = md5(uniqid(rand(), true));
// トークンをセッションに保存
$_SESSION['token'] = $token;

require_once( 'db.php' );
$max = 1000; //ページ全体での表示件数

//1000件までのデータを表示する
$result = mysql_query("SELECT * FROM `kadai_matsui_original`");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);

mysql_close($link);

//var_dump($rows,$max);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題9.10_1,BBSを作成する</title>
<link rel="stylesheet" type="text/css" href="kadai.css">
</head>
<body>
<div class="header">
	<p>♦課題9.10_1,BBSを作成する♦</p>
	<div class="kaisya"><p class="syamai">MightyCraft</p></div>
	<p class="insert"><a href = "kadai9_1_post.php" class="postPage">新規投稿する</a></p>
</div>
<br>
<form method="post" name="post_insert" action="kadai9_1_insert.php">
	名前<br>
	<input type="text" name ="name" style="width: 300px;"><br>
	<br>
	タイトル<br>
	<input type="text" name ="title" style="width: 700px;"><br>
	<br>
	本文<br>
	<textarea name="message" cols="99" rows="7"></textarea><br>
	<br><br>
<?php
	//投稿件数が1000以上以下判定(※1000件以上の投稿があれば、投稿できない)
	if ($rows < $max)
	{
		print '<input type="submit" name="toukou" value="投稿する">';
		print '<input type="hidden" name="token" value="' .$token . '">';
	}
	else
	{
		print "<input type=\"submit\" value=\"投稿する\" onclick=\"alert('投稿件数が1000件を超えているため投稿できません!!');return false;\">";
	}
?>
	<input type="reset" value="リセット">
</form>


</body>
</html>
