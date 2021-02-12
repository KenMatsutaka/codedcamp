<?php
/**
 * 課題番号：21-1
 * 画面:自動販売機結果画面
 * URL:http://localhost:80/codecamp/21_1_drink/result.php
 */
// 画面出力情報
// ドリンク情報
$drink_info = null;
// おつり
$change = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // ドリンクID
    $drink_id = null;
    if (isset($_GET["drink_id"])) {
        $drink_id = $_GET["drink_id"];
    }
    // 金額
    $money = 0;
    if (isset($_GET["money"])) {
        $money = $_GET["money"];
    }
    $link = getDBLink();
    if ($link) {
        // ドリンク情報取得
        $drink_info = findDrinkInfoById($link, $drink_id);
        // おつり計算
        $change = $money - $drink_info["price"];
    }
    // DBコネクションクローズ
    mysqli_close($link);
}

/**
 * ドリンクIDを元にドリンク情報を取得する。
 * @param $link DBコネクション
 * @param $drink_id ドリンクID
 * @return ドリンク情報
 */
function findDrinkInfoById($link, $drink_id) {
    $retList = [];
    $query  =  " SELECT";
    $query .=  "   DRINK_ID,";
    $query .=  "   DRINK_NAME,";
    $query .=  "   PRICE,";
    $query .=  "   UPLOAD_FILE_NAME";
    $query .=  " FROM MK_DRINK_TBL";
    $query .=  " WHERE DRINK_ID = ".$drink_id.";";
    $result = mysqli_query($link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["drink_id"] = $row["DRINK_ID"];
        $rowMap["drink_name"] = $row["DRINK_NAME"];
        $rowMap["price"] = $row["PRICE"];
        $rowMap["upload_file_name"] = $row["UPLOAD_FILE_NAME"];
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList[0];
}

// ユーティリティ ==========
/**
 * 必須チェックを行う
 * @param $value 入力値
 * @return 判定結果 true:空文字・null以外 false:空文字またはnull
 */
function checkNotEmpty($value) {
    $retFlag = false;
    if ($value !== null && $value !== "") {
        $retFlag = true;
    }
    return $retFlag;
}

/**
 * 数字かチェックを行う
 * @param $value 入力値
 */
function checkNumber($value) {
    $retFlg = true;
    if (checkNotEmpty($value)) {
        if (!preg_match("/[0-9]/", $value)) {
            $retFlg = false;
        }
    }
    return $retFlg;
}

/**
 * 最大文字数以下である事をチェックする。
 * @param $value 入力値
 * @param $maxLength 最大文字数
 * @return 判定結果 true:最大文字数以内 false:最大文字数より大きい
 */
function checkMaxlength($value, $maxLength) {
    $retFlag = false;
    if (mb_strlen($value) <= $maxLength) {
        $retFlag = true;
    }
    return $retFlag;
}

/**
 * 郵便番号として妥当かチェックを行う
 * @param $value 入力値
 */
function checkZipcode($value) {
    $retFlg = true;
    if (checkNotEmpty($value)) {
        if (!preg_match("/[0-9]{7}/", $value)) {
            $retFlg = false;
        }
    }
    return $retFlg;
}

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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自動販売機結果</title>
</head>
<body>
    <h1>自動販売機結果</h1>
        <img src="<?php print "./../img/".$drink_info["drink_id"]."/".$drink_info["upload_file_name"]?>">
        <p>がしゃん！【<?php print $drink_info["drink_name"]?>】が買えました！</p>
        <p>おつりは【<?php print $change?>円】です</p>
    <footer><a href="index.php">戻る</a></footer>
</body>
</html>
