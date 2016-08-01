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

//フォームから投稿されているか判定
if (isset($_POST["toukou"]))
{
	//セッション開始
	session_start();

	//名前
	$name = htmlspecialchars($_POST["name"], ENT_QUOTES);

	//投稿時間(日本時間にセットして投稿)
	date_default_timezone_set('Asia/Tokyo');
	$timestamp = time() ; //unixタイムスタンプ設定
	$time = date("Y-m-d H:i:s",$timestamp);	//掲示板用時間フォーマット

	//タイトル
	$title = htmlspecialchars($_POST["title"], ENT_QUOTES);
	//本文
	$message = htmlspecialchars($_POST["message"], ENT_QUOTES);
	//改行を反映
	$message = nl2br($message);

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

	//入力ホームに一つでも空白があると上記エラーを表示して戻る
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

	if (isset($_SESSION["token"]))
	{
		//DBに挿入
		$sql = "INSERT INTO `kadai_matsui_original`(`name`,`time`,`title`,`message`)
				VALUES ('$name','$time','$title','$message')";
		$result = mysql_query("$sql");

	}
	else
	{
		//セッション（トークン）が設定されていない場合は二重投稿エラー表示
		print "二重投稿です<br>投稿できませんでした。<br>";
		print "<a href = \"kadai9_1.php\">掲示板に戻る</a>";
		session_destroy();
		exit();
	}


	print "<p class=\"check\">1件投稿しました</p><br><br>";

	print "<a href = \"kadai9_1.php\">掲示板に戻る</a>";
	// セッションに保存しておいたトークンの削除
	unset($_SESSION['token']);
	// セッション終了
	session_destroy();

}
else
{
	print "投稿できませんでした。<br>";
	print "<a href = \"kadai9_1.php\">掲示板に戻る</a>";
}


mysql_close($link);
?>


</body>
</html>
