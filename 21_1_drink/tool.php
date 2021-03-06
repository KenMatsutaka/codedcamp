<?php
/**
 * 課題番号：21-1
 * 画面:自動販売機管理画面
 * URL:http://localhost:80/codecamp/21_1_drink/tool.php
 */
// 画面入力情報 ==========
// 実行SQL種類
$sql_kind;
// 新規登録 ----------
// 名前
$new_name = null;
// 値段
$new_price = null;
// 個数
$new_stock = null;
//イメージファイル
$new_img = null;
// 公開ステータス
$new_status = "0";
// 在庫更新 ----------
// ドリンクID
$update_drink_id;
// 在庫数
$update_stock;
// 公開ステータス更新 ----------
// ドリンクID
$change_drink_id;
// 公開ステータス
$change_status;


// 画面出力情報 ==========
// サクセスメッセージ
$success_message="";
// エラーメッセージ情報
$error_messages = [];
// 一覧情報
$drinkList = [];

// メイン処理 ==========
// DBコネクション取得
$link = getDBLink();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["sql_kind"])) {
        $sql_kind = $_POST["sql_kind"];
    }
    // 新規登録処理
    if ($sql_kind === "insert") {
        // 名前
        if (isset($_POST["new_name"])) {
            $new_name = $_POST["new_name"];
        }
        // 値段
        if (isset($_POST["new_price"])) {
            $new_price = $_POST["new_price"];
        }
        // 個数
        if (isset($_POST["new_stock"])) {
            $new_stock = $_POST["new_stock"];
        }
        if (is_uploaded_file($_FILES['new_img']['tmp_name'])) {
            $new_img = $_FILES['new_img'];
        }
        // 公開ステータス
        if (isset($_POST["new_status"])) {
            $new_status = $_POST["new_status"];
        }
        // 入力チェック
        if (checkInputValueForInsert()) {
            // ドリンク情報登録
            $drink_info = [
                "drink_name" => $new_name,
                "price" => $new_price,
                "stock" => $new_stock,
                "upload_file" => $new_img,
                "open_status" => $new_status
            ];
            saveDrinkInfo($link, $drink_info);
        }
    // 在庫更新
    } else if ($sql_kind === "update") {
        // ドリンクID
        if (isset($_POST["update_drink_id"])) {
            $update_drink_id = $_POST["update_drink_id"];
        }
        // 在庫数
        if (isset($_POST["update_stock"])) {
            $update_stock = $_POST["update_stock"];
        }
        // 入力チェック
        if (checkInputValueForUpdate()){
            // 在庫情報更新
            $stock_info = [
                "drink_id" => $update_drink_id,
                "stock_count" => $update_stock
            ];
            saveStock($link, $stock_info);
        }
    // 公開ステータス更新
    } else if($sql_kind === "change") {
        // ドリンクID
        if(isset($_POST["change_drink_id"])) {
            $change_drink_id = $_POST["change_drink_id"];
        }
        // 公開ステータス
        if(isset($_POST["change_status"])) {
            $change_status = $_POST["change_status"];
        }
        // 入力チェック
        if (checkInputVallueChange()) {
            // 公開ステータス更新
            $open_status_info = [
                "drink_id" => $change_drink_id,
                "open_status" => $change_status
            ];
            saveOpenStatusInfo($link, $open_status_info);
        }
    }
}
// 一覧情報取得
$drinkList = findAllDrinkInfo($link);
// DBコネクションクローズ
mysqli_close($link);

// 画面固有関数 ==========
/**
 * 新規登録時の入力項目のチェックを行う。
 * @return 判定結果 true:OK false:NG
 */
function checkInputValueForInsert() {
    global $error_messages;
    global $new_name, $new_price, $new_stock, $new_img, $new_status;
    // 名前 ----------
    // 必須チェック
    if (!checkNotEmpty($new_name)) {
        $error_messages[] = "名前は必須です。";
    }
    // 値段 ----------
    // 必須チェック
    if (!checkNotEmpty($new_price)) {
        $error_messages[] = "値段は必須です。";
    }
    // 数字チェック
    if (!checkNumber($new_price)) {
        $error_messages[] = "値段は数字を入力して下さい。";
    }
    // 個数 ----------
    //　必須チェック
    if (!checkNotEmpty($new_stock)) {
        $error_messages[] = "個数は必須です。";
    }
    // 数字チェック
    if (!checkNumber($new_stock)) {
        $error_messages[] = "個数は数字を入力して下さい。";
    }
    // ファイル ----------
    // 必須チェック
    if (!checkNotEmpty($new_img)) {
        $error_messages[] = "ファイルは必須です。";
    }
    // 公開ステータス
    if (!preg_match("/0|1/",$new_status)) {
        $error_messages[] = "公開ステータスが不正です。";
    }
    // チェック結果の判定
    $retFlag = false;
    if (count($error_messages) === 0) {
        $retFlag = true;
    }
    return $retFlag;
}

/**
 * 在庫更新時の入力チェックを行う。
 * @return 判定結果 true:OK false:NG
 */
function checkInputValueForUpdate() {
    global $error_messages;
    global $update_stock;
    // 在庫数 ----------
    // 必須チェック
    if (!checkNotEmpty($update_stock)) {
        $error_messages[] = "在庫数は必須です。";
    }
    // 数字チェック
    if (!checkNumber($update_stock)) {
        $error_messages[] = "在庫数は数字で入力して下さい。";
    }
    // チェック結果の判定
    $retFlag = false;
    if (count($error_messages) === 0) {
        $retFlag = true;
    }
    return $retFlag;
}

/**
 * 公開ステータス更新時の入力項目チェックを行う。
 * @return 判定結果 true:OK false:NG
 */
function checkInputVallueChange() {
    global $change_status;
    global $error_messages;
    // 公開ステータス
    if (!preg_match("/0|1/",$change_status)) {
        $error_messages[] = "公開ステータスが不正です。";
    }
    // チェック結果の判定
    $retFlag = false;
    if (count($error_messages) === 0) {
        $retFlag = true;
    }
    return $retFlag;
}

/**
 * ドリンク情報を登録する。
 * @param $link DBコネクション
 * @param $drinkInfo ドリンク情報
 */
function saveDrinkInfo($link, $drinkInfo) {
    global $success_message, $error_messages;
    //システム日付
    $date = date('Y-m-d H:i:s');
    //ドリンク情報登録
    mysqli_autocommit($link, false);
    $drinkQuery = "INSERT INTO MK_DRINK_TBL (DRINK_NAME, PRICE, INSERT_DATE, UPDATE_DATE, OPEN_STATUS, UPLOAD_FILE_NAME) VALUES";
    $drinkQuery .= "('".$drinkInfo["drink_name"]."', ".$drinkInfo["price"].", '".$date."', '".$date."', ".$drinkInfo["open_status"].", '".$drinkInfo["upload_file"]["name"]."');";
    $result = mysqli_query($link, $drinkQuery);
    if ($result === true) {
        // 在庫テーブル
        $drinkId = mysqli_insert_id($link);
        $stockQuery = "INSERT INTO MK_STOCK_TBL (DRINK_ID, STOCK_COUNT, INSERT_DATE, UPDATE_DATE) VALUES";
        $stockQuery .= "(".$drinkId.", ".$drinkInfo["stock"].", '".$date."', '".$date."');";
        $result = mysqli_query($link, $stockQuery);

        if($result === true) {
            // アップロードファイルの格納
            mkdir("./../img/".$drinkId, 0777);
            move_uploaded_file($drinkInfo["upload_file"]["tmp_name"], "./../img/".$drinkId."/".$drinkInfo["upload_file"]["name"]);
            // サクセスメッセージ
            $success_message = "ドリンクの登録が完了しました。";
        } else {
            $error_messages[] = "SQL実行失敗:".$stockQuery;
        }
    } else {
        $error_messages[] = "SQL実行失敗:".$drinkQuery;
    }
    if (count($error_messages) === 0) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
}

/**
 * 在庫情報を更新する
 * @param $link DBコネクション
 * @param $stockInfo 在庫情報
 */
function saveStock($link, $stockInfo) {
    global $success_message, $error_messages;
    //システム日付
    $date = date('Y-m-d H:i:s');
    //ドリンク情報登録
    mysqli_autocommit($link, false);
    $stockQuery  = " UPDATE MK_STOCK_TBL ";
    $stockQuery .= " SET STOCK_COUNT = ".$stockInfo["stock_count"].",";
    $stockQuery .= "     UPDATE_DATE = '".$date."' ";
    $stockQuery .= " WHERE DRINK_ID = ".$stockInfo["drink_id"].";";
    $result = mysqli_query($link, $stockQuery);

    if ($result === true) {
        $success_message = "在庫の更新が完了しました。";
    } else {
        $error_messages[] = "SQL実行失敗:".$stockQuery;
    }
    if (count($error_messages) === 0) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
}

/**
 * 公開ステータスを更新
 * @param $link DBコネクション
 * @param $openStatusInfo 公開ステータス情報
 */
function saveOpenStatusInfo($link, $openStatusInfo) {
    global $success_message, $error_messages;
    // システム日付
    $date = date('Y-m-d H:i:s');
    // 公開ステータス更新
    mysqli_autocommit($link, false);
    $openStatusQuery  = " UPDATE MK_DRINK_TBL ";
    $openStatusQuery .= " SET OPEN_STATUS = ".$openStatusInfo["open_status"].", ";
    $openStatusQuery .= "     UPDATE_DATE = '".$date."'";
    $openStatusQuery .= " WHERE DRINK_ID = ".$openStatusInfo["drink_id"].";";
    $result = mysqli_query($link, $openStatusQuery);
    if ($result === true) {
        $success_message = "ステータスの更新が完了しました。";
    } else {
        $error_messages[] = "SQL実行失敗:".$openStatusQuery;
    }
    if (count($error_messages) === 0) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
}

/**
 * ドリンク情報を取得する。
 * @param $link DBコネクション
 * @return ドリンク情報
 */
function findAllDrinkInfo($link) {
    $retList = [];
    $query =  " SELECT ";
    $query .= "   dt.DRINK_ID,";
    $query .= "   dt.DRINK_NAME,";
    $query .= "   dt.PRICE,";
    $query .= "   dt.OPEN_STATUS,";
    $query .= "   st.STOCK_COUNT,";
    $query .= "   dt.UPLOAD_FILE_NAME";
    $query .= " FROM MK_DRINK_TBL dt";
    $query .= " INNER JOIN MK_STOCK_TBL st";
    $query .= " ON dt.DRINK_ID = st.DRINK_ID";
    $query .= " ORDER BY st.DRINK_ID ASC";

    $result = mysqli_query($link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        // ドリンクID
        $rowMap["drink_id"] = $row["DRINK_ID"];
        // ドリンク名
        $rowMap["drink_name"] = $row["DRINK_NAME"];
        // 値段
        $rowMap["price"] = $row["PRICE"];
        // 公開ステータス
        $rowMap["open_status"] = $row["OPEN_STATUS"];
        // ファイル名
        $rowMap["upload_file_name"] = $row["UPLOAD_FILE_NAME"];
        // 在庫数
        $rowMap["stock_count"] = $row["STOCK_COUNT"];
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList;
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
    <title>自動販売機</title>
    <style>
        section {
            margin-bottom: 20px;
            border-top: solid 1px;
        }
        table {
            width: 660px;
            border-collapse: collapse;
        }
        table, tr, th, td {
            border: solid 1px;
            padding: 10px;
            text-align: center;
        }
        caption {
            text-align: left;
        }
        .text_align_right {
            text-align: right;
        }
        .drink_name_width {
            width: 100px;
        }
        .input_text_width {
            width: 60px;
        }
        .status_false {
            background-color: #A9A9A9;
        }
        .error_message {
            color: #FF0000;
        }
        .success_message {
            color: #0000FF;
        }
    </style>
</head>
<body>
    <h1>自動販売機管理ツール</h1>
    <p class="success_message"><?php print $success_message; ?></p>
    <?php foreach($error_messages as $error_message) { ?>
        <p class="error_message"><?php print $error_message; ?></p>
    <?php } ?>
    <section>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <div><label>名前: <input type="text" name="new_name" value="<?php print $new_name;?>"></label></div>
            <div><label>値段: <input type="text" name="new_price" value="<?php print $new_price;?>"></label></div>
            <div><label>個数: <input type="text" name="new_stock" value="<?php print $new_stock?>"></label></div>
            <div><input type="file" name="new_img"></div>
            <div>
                <select name="new_status">
                    <option value="0" <?php print $new_stock === "0" ? "selected":"";?>>非公開</option>
                    <option value="1" <?php print $new_stock === "1" ? "selected":"";?>>公開</option>
                </select>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="■□■□■商品追加■□■□■"></div>
        </form>
    </section>
    <section>
        <h2>商品情報変更</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
            </tr>
            <?php foreach($drinkList as $drink) {?>
                <tr class="<?php if($drink["open_status"] === "0") {print "status_false";}?>">
                    <form method="post">
                        <td><img src="<?php print "./../img/".$drink["drink_id"]."/".$drink["upload_file_name"];?>"></td>
                        <td class="drink_name_width"><?php print $drink["drink_name"]?></td>
                        <td class="text_align_right"><?php print $drink["price"]?></td>
                        <td><input type="text"  class="input_text_width text_align_right" name="update_stock" value="<?php print $drink["stock_count"]?>">個&nbsp;&nbsp;<input type="submit" value="変更"></td>
                        <input type="hidden" name="update_drink_id" value="<?php print $drink["drink_id"]?>">
                        <input type="hidden" name="sql_kind" value="update">
                    </form>
                    <form method="post">
                        <td><input type="submit" value="<?php print $drink["open_status"] === "0" ? "非公開 → 公開" : "公開 → 非公開";?>"></td>
                        <input type="hidden" name="change_status" value="<?php print $drink["open_status"] === "0" ? "1" : "0";?>">
                        <input type="hidden" name="change_drink_id" value="<?php print $drink["drink_id"]?>">
                        <input type="hidden" name="sql_kind" value="change">
                    </form>
                <tr>
            <?php }?>
        </table>
    </section>
</body>
</html>
