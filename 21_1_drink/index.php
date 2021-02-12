<?php 
/**
 * 課題番号：21-1
 * 画面:購入ページ画面
 * URL:http://localhost:80/codecamp/21_1_drink/index.php
 */
// 画面入力情報 ==========
// ドリンクID
$drink_id;
// 金額
$money;
// 値段
$price;

// 画面出力情報 ==========
// ドリンク一覧情報
$drinkList = [];
//エラーメッセージ
$error_messages = [];

// メイン処理 ==========
// DBコネクション取得
$link = getDBLink();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ドリンクID
    if (isset($_POST["drink_id"])) {
        $drink_id = $_POST["drink_id"];
    }
    // 金額
    if (isset($_POST["money"])) {
        $money = $_POST["money"];
    }
    // 値段
    if (isset($_POST["price"])) {
        $price = $_POST["price"];
    }
    // FIXME 入力チェック
    $saveInfo = [
        "drink_id" => $drink_id
    ];
    // FIXME テスト用にコメントアウト
    $saveResult = saveBuyInfo($link, $saveInfo);
    if ($saveResult === true) {
      // 次画面へ遷移
      header("Location: result.php?drink_id=".$drink_id."&money=".$money);
    }

}
// 一覧情報
$drinkList = findDrinkList($link);
// DBコネクションクローズ
mysqli_close($link);

// 画面固有関数 ==========
/**
 * 購入情報の登録を行う。
 * @param $link DBコネクション
 * @param $saveInfo 登録情報
 * @return 更新結果 true:更新成功 false:更新失敗
 */
function saveBuyInfo($link, $saveInfo) {
    global $error_messages;
    $retFlag = false;
    //システム日付
    $date = date('Y-m-d H:i:s');
    //ドリンク情報登録
    mysqli_autocommit($link, false);
    // 在庫情報取得
    $querySelectStock  = " SELECT DRINK_ID, STOCK_COUNT ";
    $querySelectStock .= " FROM MK_STOCK_TBL";
    $querySelectStock .= " WHERE DRINK_ID = ".$saveInfo["drink_id"];
    $querySelectStock .= " FOR UPDATE;";

    $result = mysqli_query($link, $querySelectStock);
    // 検索結果の設定
    $stockList = [];
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["drink_id"] = $row["DRINK_ID"];
        $rowMap["stock"] = $row["STOCK_COUNT"];
        $stockList[] = $rowMap;
    }
    $stock = $stockList[0]["stock"] - 1;
    // 在庫情報更新
    $queryUpdateStock  = " UPDATE MK_STOCK_TBL ";
    $queryUpdateStock .= " SET STOCK_COUNT = ".$stock.", UPDATE_DATE = '".$date."'";
    $queryUpdateStock .= " WHERE DRINK_ID = ".$saveInfo["drink_id"].";";
    $result = mysqli_query($link, $queryUpdateStock);
    if ($result === true) {
        // 購入履歴登録
        $queryInsertBuyHistory  = " INSERT INTO MK_BUY_HISTORY_TBL (DRINK_ID, BUY_DATE)";
        $queryInsertBuyHistory .= " VALUE (".$saveInfo["drink_id"].", '".$date."');";
        $result = mysqli_query($link, $queryInsertBuyHistory);
        if ($result === true) {
            $retFlag = true;
        } else {
            $error_messages[] = "SQL実行失敗:".$queryInsertBuyHistory;
        }
    } else {
        $error_messages[] = "SQL実行失敗:".$queryUpdateStock;
    }
    if (count($error_messages) === 0) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
    return $retFlag;
}

/**
 * ドリンク一覧を取得する。
 * @param $link　DBコネクション
 * @return ドリンク一覧情報
 */
function findDrinkList($link) {
    $retList = [];
    $query  = "  SELECT ";
    $query .= "    mdt.DRINK_ID,";
    $query .= "    mdt.DRINK_NAME,";
    $query .= "    mdt.PRICE,";
    $query .= "    mst.STOCK_COUNT,";
    $query .= "    mdt.OPEN_STATUS,";
    $query .= "    mdt.UPLOAD_FILE_NAME";
    $query .= "  FROM MK_DRINK_TBL mdt";
    $query .= "  INNER JOIN MK_STOCK_TBL mst";
    $query .= "  ON mdt.DRINK_ID = mst.DRINK_ID";
    $query .= "  WHERE mdt.OPEN_STATUS = 1";
    $result = mysqli_query($link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["drink_id"] = $row["DRINK_ID"];
        $rowMap["drink_name"] = $row["DRINK_NAME"];
        $rowMap["price"] = $row["PRICE"];
        $rowMap["stock"] = $row["STOCK_COUNT"];
        $rowMap["status"] = $row["OPEN_STATUS"];
        $rowMap["file_name"] = $row["UPLOAD_FILE_NAME"];
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
        #flex {
            width: 600px;
        }

        #flex .drink {
            //border: solid 1px;
            width: 120px;
            height: 210px;
            text-align: center;
            margin: 10px;
            float: left; 
        }

        #flex span {
            display: block;
            margin: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .img_size {
            height: 125px;
        }

        .red {
            color: #FF0000;
        }

        #submit {
            clear: both;
        }

    </style>
</head>
<body>
    <h1>自動販売機</h1>
    <?php foreach($error_messages as $error_message) { ?>
        <p class="error_message"><?php print $error_message; ?></p>
    <?php } ?>
    <form method="post">
        <div>金額<input type="text" name="money" value=""></div>
        <div id="flex">
            <?php foreach($drinkList as $drink) {?>
                <div class="drink">
                    <span class="img_size"><img src="./../img/<?php print $drink["drink_id"];?>/<?php print $drink["file_name"]?>"></span>
                    <span><?php print $drink["drink_name"];?></span>
                    <span><?php print $drink["price"];?>円</span>
                    <?php if ($drink["stock"] === 0) {?>
                        <span class="red">売り切れ</span>
                    <?php } else {?>
                        <input type="radio" name="drink_id" value="<?php print $drink["drink_id"];?>">
                        <input type="hidden" name="price" value="<?php print $drink["price"]?>">
                    <?php }?>
                </div>
            <?php }?>
        </div>
        <div id="submit">
            <input type="submit" value="■□■□■ 購入 ■□■□■">
        </div>
    </form>
</body>
</html>