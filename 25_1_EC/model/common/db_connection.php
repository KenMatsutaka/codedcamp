<?php
/**
 * データベースコネクションを取得する。
 * @return データベースコネクション
 */
function getDBLink() {
    // DBの接続情報
    $host = "localhost";
    $userName = "root";
    $passWord = "";
    $dbName = "codecamp";
    $link = mysqli_connect($host, $userName, $passWord, $dbName);
    // 接続状況をチェックします
    if (mysqli_connect_errno()) {
        die("データベースに接続できません:" . mysqli_connect_error() . "\n");
    }
    return $link;
}
