<?php
/**
 * 購入テーブル(MY_BUY_ITEM_TBL)操作用のファイル
 */
/**
 * 購入した商品の合計金額を取得する。
 * @param $db_link DBコネクション
 * @param $buy_code 商品コード
 * @param 購入商品合計金額
 */
function sum_buy_price($db_link, $buy_code) {
    $query  = " SELECT SUM(BUY_PRICE * AMOUNT) TOTAL_PRICE";
    $query .= " FROM MK_BUY_ITEM_TBL ";
    $query .= " WHERE BUY_CODE = {$buy_code};";
    $result = mysqli_query($db_link, $query);
    $total_price = 0;
    while ($row = mysqli_fetch_array($result)) {
        $total_price = $row["TOTAL_PRICE"];
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $total_price;
}

/**
 * 商品コードを元に購入商品情報を取得する。
 * @param $db_link DBコネクション
 * @param $buy_code 商品コード
 * @param 購入商品情報
 */
function find_buy_item_list($db_link, $buy_code) {
    $ret_list = [];
    $query  = " SELECT ";
    $query .= "     mbit.BUY_CODE,";
    $query .= "     mbit.SEQ,";
    $query .= "     mit.ID ITEM_ID,";
    $query .= "     mit.NAME,";
    $query .= "     mbit.BUY_PRICE,";
    $query .= "     mbit.AMOUNT,";
    $query .= "     mit.IMG,";
    $query .= "     mbit.CREATED_DATE,";
    $query .= "     mbit.UPDATE_DATE";
    $query .= " FROM MK_BUY_ITEM_TBL mbit";
    $query .= " INNER JOIN MK_ITEM_TBL mit";
    $query .= " ON  mbit.ITEM_ID = mit.ID";
    $query .= " WHERE mbit.BUY_CODE = {$buy_code}";
    $query .= " ORDER BY mbit.SEQ ASC;";
    $result = mysqli_query($db_link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $row_map = [];
        $row_map["buy_code"] = $row["BUY_CODE"];
        $row_map["seq"] = $row["SEQ"];
        $row_map["item_id"] = $row["ITEM_ID"];
        $row_map["name"] = $row["NAME"];
        $row_map["buy_price"] = $row["BUY_PRICE"];
        $row_map["amount"] = $row["AMOUNT"];
        $row_map["img"] = $row["IMG"];
        $row_map["created_date"] = $row["CREATED_DATE"];
        $row_map["update_date"] = $row["UPDATE_DATE"];
        $ret_list[] = $row_map;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $ret_list;
}

/**
 * 商品コード採番する。
 * @param $db_link DBコネクション
 * @return 商品コード
 */
 function numbering_buy_code($db_link) {
    $query = "SELECT IFNULL(MAX(BUY_CODE),0)+1 NEW_BUY_CODE FROM MK_BUY_ITEM_TBL FOR UPDATE;";
    $result = mysqli_query($db_link, $query);
    $buy_code = 0;
    while ($row = mysqli_fetch_array($result)) {
        $buy_code = $row["NEW_BUY_CODE"];
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $buy_code;
}

/**
 * 購入商品情報の登録を行う。
 * @param $db_link DBコネクション
 * @param $user_id　ユーザID
 * @param $buy_code 商品コード
 * @param $save_info_list 登録情報リスト
 * @return 登録結果
 */
function save_buy_item($db_link, $user_id, $buy_code, $save_info_list) {
    //システム日付
    $date = date('Y-m-d H:i:s');
    // 購入商品情報登録
    $insertQuery  = " INSERT INTO MK_BUY_ITEM_TBL (BUY_CODE, SEQ, USER_ID, ITEM_ID, BUY_PRICE, AMOUNT, CREATED_DATE, UPDATE_DATE)";
    $insertQuery .= " VALUES";
    $index = 1;
    foreach($save_info_list as $save_info) {
        $insertQuery .= $index !== 1 ? "," : "";
        $insertQuery .= "({$buy_code}, {$index}, {$user_id}, {$save_info['item_id']}, {$save_info['price']}, {$save_info['amount']}, '{$date}', '{$date}') ";
        $index++;
    }
    $result = mysqli_query($db_link, $insertQuery);
    $save_result_info = ["result" => $result];
    if ($result === true) {
        $save_result_info["message"] = "購入情報の登録が完了しました。";
    } else {
        $save_result_info["message"] = "SQL実行失敗:".$query;
    }
    return $save_result_info;
}
