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

if (isset($_GET["id"]))
{
	$id = $_GET["id"];

	$sql = "DELETE FROM  `kadai_matsui_original` WHERE `id` = '$id'";
	$result = mysql_query("$sql");


	//DB削除チェック
	if ($result)
	{
		print "1件削除しました。<br><br>";
	}
	else
	{
		print "削除できませんでした。<br><br>";
	}
}
else
{
	print "削除できませんでした。<br><br>";
}


print "<a href = \"kadai9_1.php\">掲示板に戻る</a>";


mysql_close($link);
?>


</body>
</html>
