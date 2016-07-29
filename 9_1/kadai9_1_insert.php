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
<?php
//DB接続
require_once( 'db.php' );

//名前
$name = htmlspecialchars($_POST["name"], ENT_QUOTES);

//投稿時間
$timestamp = time() ; //unixタイムスタンプ設定
$time = date("Y-m-d H:i:s",$timestamp);	//掲示板用時間フォーマット
//$time = date("Y年m月d日 H時i分s秒",$timestamp);

//タイトル
$title = htmlspecialchars($_POST["title"], ENT_QUOTES);
//本文
$message = htmlspecialchars($_POST["message"], ENT_QUOTES);

//エラー変数初期化
$error = "";
//名前エラー
if ($name == "")
{
	$error .= "名前を入力してください!!<br>";
}
//タイトルエラー
if ($title == "")
{
	$error .= "タイトルを入力してください!!<br>";
}
//本文エラー
if ($message == "")
{
	$error .= "本文を入力してください!!<br>";
}


if ($name == "" || $title == "" || $message == "")
{
	print <<<EOF

		<form>
			<p>{$error}</p><br><br>
			<input type="button" onClick='history.back();' value="戻る">
		</form>
EOF;
	exit();

}

//DBに挿入
$sql = "INSERT INTO `kadai_matsui_original`(`name`,`time`,`title`,`message`)
		VALUES ('$name','$time','$title','$message')";

$result = mysql_query("$sql");


print "<p class=\"check\">1件投稿しました</p><br><br>";

print "<a href = \"kadai9_1.php\">掲示板に戻る</a>";


mysql_close($link);
?>


</body>
</html>
