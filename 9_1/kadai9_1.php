<?php
require_once( 'db.php' );
require_once( 'kadai9_1_pager.php' );


$limit = 50; //1ページでの表示件数
$max = 1000; //ページ全体での表示件数

//1000件までのデータを表示する
$result = mysql_query("SELECT * FROM `kadai_matsui_original` LIMIT 0 , $max");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);

$maxError = "";	//投稿が1000を超える場合は警告表示
if ($rows == $max)
{
	$maxError = "<p class=\"max\">※投稿件数が1000件になりました。これ以上は投稿できません。</p>";
}

if(isset($_GET["page"]))
{
	//ページリンク押した場合GET値取得、偽の場合1
	$page = $_GET["page"];
	//ページ情報をクッキーに保存
	$cookie_offset = $page;
	setcookie("cookie_offset", $cookie_offset);
	$_COOKIE["cookie_offset"] = $cookie_offset;
	$obj->pager($page, $rows);
}
else
{
	$page = 1;
	$obj->pager($page, $rows);
}


if (isset($_COOKIE["cookie_offset"]))
{
	$page = $_COOKIE["cookie_offset"];
}

//ページ-1×表示件数（何ページ目かを設定）
$offset = ($page - 1)*$limit;

//全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
if ($obj->total_page < $obj->show_nav)
{
	$obj->show_nav = $obj->total_page;
}

//総ページの半分
$obj->show_navh = floor($obj->show_nav / 2);
//現在のページをナビゲーションの中心にする
$loop_start = $obj->current_page - $obj->show_navh;
$loop_end = $obj->current_page + $obj->show_navh;
//現在のページが両端だったら端にくるようにする
if ($loop_start <= 0)
{
	$loop_start  = 1;
	$loop_end = $obj->show_nav;
}
if ($loop_end > $obj->total_page)
{
	$loop_start  = $obj->total_page - $obj->show_nav +1;
	$loop_end =  $obj->total_page;
}

/*
 * DBのレコード数が表示レコード数を下回っていれば
 * ページリンク表示しない。
 */
//ページリンク
$pageLink = "";

if ($rows >= $obj->page_rec)
{
	//ページ情報がクッキーに設定されているか判定
	if (isset($_COOKIE["cookie_offset"]))
	{
	//2ページ移行だったら「一番前へ」を表示
		if ( $_COOKIE["cookie_offset"] > 2)
		{
			$pageLink .= '<li class="prev"><a href="'. $obj->path .'1">&laquo;</a></li>';
		}
		//最初のページ以外だったら「前へ」を表示
		if ( $_COOKIE["cookie_offset"] > 1)
		{
			$pageLink .= '<li class="prev"><a href="'. $obj->path . ($obj->current_page-1).'">&lsaquo;</a></li>';
		}
	}
	else
	{
		//2ページ移行だったら「一番前へ」を表示
		if ( $obj->current_page > 2)
		{
			$pageLink .= '<li class="prev"><a href="'. $obj->path .'1">&laquo;</a></li>';
		}
			//最初のページ以外だったら「前へ」を表示
		if ( $obj->current_page > 1)
		{
			$pageLink .= '<li class="prev"><a href="'. $obj->path . ($obj->current_page-1).'">&lsaquo;</a></li>';
		}
	}
	//ページ情報がクッキーに設定されているか判定
	if (isset($_COOKIE["cookie_offset"]))
	{
		for ($i=$loop_start; $i<=$loop_end; $i++)
		{
			//クッキーに保存されたページ情報の一致判定
			if($i == $_COOKIE["cookie_offset"])
			{
				//現在のページ
				$pageLink .= '<li class="active">';
				$pageLink .= $i;
				$pageLink .= '</li>';
			}
			else
			{
				if ($i > 0 && $obj->total_page >= $i)
				{
					//その他のページ
					$pageLink .= '<li>';
					$pageLink .= '<a href="'. $obj->path . $i.'">'.$i.'</a>';
					$pageLink .= '</li>';
				 }
			}
		}
	}
	else
	{
		for ($i=$loop_start; $i<=$loop_end; $i++)
		{
			if ($i > 0 && $obj->total_page >= $i)
			{
				if($i == $obj->current_page)
				{
					//現在のページ
					$pageLink .= '<li class="active">';
					$pageLink .= $i;
					$pageLink .= '</li>';
				}
				else
				{
					//その他のページ
					$pageLink .= '<li>';
					$pageLink .= '<a href="'. $obj->path . $i.'">'.$i.'</a>';
					$pageLink .= '</li>';
				}
			}
		}
	}
	//ページ情報がクッキーに設定されているか判定
	if (isset($_COOKIE["cookie_offset"]))
	{
		//最後のページ以外だったら「次へ」を表示
		if ( $_COOKIE["cookie_offset"] < $obj->total_page)
		{
			$pageLink .= '<li class="next"><a href="'. $obj->path . ($obj->current_page+1).'">&rsaquo;</a></li>';
		}
		//最後から２ページ前だったら「一番最後へ」を表示
		if ( $_COOKIE["cookie_offset"] < $obj->total_page - 1)
		{
			$pageLink .= '<li class="next"><a href="'. $obj->path . $obj->total_page.'">&raquo;</a></li>';
		}
	}
	else
	{
		//最後のページ以外だったら「次へ」を表示
		if ( $obj->current_page < $obj->total_page)
		{
			$pageLink .= '<li class="next"><a href="'. $obj->path . ($obj->current_page+1).'">&rsaquo;</a></li>';
		}
		//最後から２ページ前だったら「一番最後へ」を表示
		if ( $obj->current_page < $obj->total_page - 1)
		{
			$pageLink .= '<li class="next"><a href="'. $obj->path . $obj->total_page.'">&raquo;</a></li>';
		}
	}
}
//降順でDB標示
$sql = "SELECT `id`, `name`, `time`, `title`, `message` FROM `kadai_matsui_original` ORDER BY `id` DESC LIMIT {$offset},{$limit}";

$result = mysql_query("$sql");

$i=0;
while ($row = mysql_fetch_array($result))
{
	$data_list[$i] = "<div class=\"dataFlid\">";
	$data_list[$i] .= "<div class=\"datalist\">";
	$data_list[$i] .= "<p class=\"id\">■{$row['id']}</p>";
	$data_list[$i] .= "<p class=\"title\">{$row['title']}</p>";
	$data_list[$i] .= "</div>";
	$data_list[$i] .= "<div class=\"dataname\">";
	$data_list[$i] .= "<p class=\"name\">□投稿者 / {$row['name']}</p>";
	$data_list[$i] .= "<p class=\"time\">({$row['time']})</p>";
	$data_list[$i] .= "</div>";
	$data_list[$i] .= "<div class=\"mes\">";
	$data_list[$i] .= "<p>{$row['message']}</p>";
	$data_list[$i] .= "<p class=\"deletePages\"><a href=\"kadai9_1_delete.php?id={$row['id']}\" onclick=\"return confirm('本当に削除しますか？')\">[削除]</a></p>";
	$data_list[$i] .= "</div>";
	$data_list[$i] .= "</div>";

	$i++;
}

mysql_close($link);

//HTML書き出し
print <<<EOF

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題9.10_1,BBSを作成する</title>
<link rel="stylesheet" type="text/css" href="kadai.css">
</head>
<body>
	<!-- ヘッダー -->
	<div class="header">
		<p>♦課題9.10_1,BBSを作成する♦</p>
		<div class="kaisya"><p class="syamai">MightyCraft</p></div>
		<p class="insert"><a href = "kadai9_1_post.php" class="postPage">新規投稿する</a></p>
	</div>
	<!-- ヘッダー -->
	<br>

	<!-- ページャー -->
	<div id="pagenation">
		<ul>
			{$pageLink}
		</ul>
	</div>
	<!-- ページャー -->

	<!-- 掲示板リスト -->
	<p class="max">投稿件数「<span>{$rows}</span>」件</p>
	{$maxError}
EOF;
	if (isset($data_list))
	{
		//DBリストを取得
		foreach ($data_list as $key => $value)
		{
			print $value;
		}
	}


print <<<EOF
	<!-- 掲示板リスト -->

	<!-- ページャー -->
	<div id="pagenation">
		<ul>
			{$pageLink}
		</ul>
	</div>
	<!-- ページャー -->

</body>
</html>

EOF;

