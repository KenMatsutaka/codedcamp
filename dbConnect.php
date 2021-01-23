<?php
/**
 * DB接続サンプル
 * http://localhost:80/codecamp/dbconnect.php
 */
$link = mysqli_connect('localhost', 'root', '', 'matudb');

// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
}

// カラム`id`には`1`を、カラム`name`には`yamada`をもつレコードを挿入する
$query = "select id, col1 from test_tbl;";

// クエリを実行します。
if ($result = mysqli_query($link, $query)) {
  foreach ($result as $row) {
    echo "id:".$row["id"]."  col1:".$row["col1"]."<br>";
  }
}

// 接続を閉じます
mysqli_close($link);

?>