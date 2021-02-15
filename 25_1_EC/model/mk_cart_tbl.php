<?php
/**
 * カートテーブル(MK_CART_TBL)操作用のファイル
 */

 /**
  * カート情報を取得する。
  * @param 
  */
function find_cart($db_link, $search_condition, $count_flag = false) {
    $retList = [];
    $query  = " SELECT ";
    if ($count_flag) {
        $query .= "   COUNT(*) RECORD_COUNT";
    } else {
        $query .= "   MCT.ID CART_ID,";
        $query .= "   MCT.USER_ID,";
        $query .= "   MCT.ITEM_ID,";
        $query .= "   MCT.AMOUNT,";
        $query .= "   MIT.NAME,";
        $query .= "   MIT.PRICE,";
        $query .= "   MIT.IMG,";
        $query .= "   MIT.STATUS,";
        $query .= "   MCT.CREATED_DATE,";
        $query .= "   MCT.UPDATE_DATE";
    }
    $query .= " FROM MK_CART_TBL MCT";
    $query .= " INNER JOIN MK_ITEM_TBL MIT";
    $query .= " ON MCT.ITEM_ID = MIT.ID";
    $query .= " WHERE MCT.USER_ID = ".$search_condition["user_id"];
    if (isset($search_condition["item_id"])) {
        $query .= "AND MCT.ITEM_ID = ".$search_condition["item_id"];
    }
    $result = mysqli_query($db_link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        if ($count_flag) {
            $rowMap["record_count"] = $row["RECORD_COUNT"];
        } else {
            $rowMap["cart_id"] = $row["CART_ID"];
            $rowMap["user_id"] = $row["USER_ID"];
            $rowMap["item_id"] = $row["ITEM_ID"];
            $rowMap["amount"] = $row["AMOUNT"];
            $rowMap["name"] = $row["NAME"];
            $rowMap["price"] = $row["PRICE"];
            $rowMap["img"] = $row["IMG"];
            $rowMap["status"] = $row["STATUS"];
            $rowMap["created_date"] = $row["CREATED_DATE"];
            $rowMap["update_date"] = $row["UPDATE_DATE"];
        }
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList;
}

/**
 * カート情報を登録する。
 * @param $db_link DBコネクション
 * @param $save_info 登録情報
 * @return 登録結果
 */
function add_cart($db_link, $save_info) {
    $query  = " INSERT INTO MK_CART_TBL (USER_ID, ITEM_ID, AMOUNT, CREATED_DATE, UPDATE_DATE)";
    $query .= " VALUES ({$save_info['user_id']}, {$save_info['item_id']}, {$save_info['amount']}, '{$save_info['created_date']}', '{$save_info['update_date']}');";
    $result = mysqli_query($db_link, $query);
    $save_result_info = ["result" => $result];
    if ($result === true) {
        $save_result_info["message"] = "カートへの追加が完了しました。";
    } else {
        $save_result_info["message"] = "SQL実行失敗:".$query;
    }
    return $save_result_info;
}

/**
 * カート情報を削除する。
 * @param $db_link DBコネクション
 * @param $remove_condition 削除条件
 * @return 登録結果
 */
function remove_cart($db_link, $remove_condition) {
    $query  = " DELETE FROM MK_CART_TBL";
    $query .= " WHERE USER_ID = {$remove_condition['user_id']}";
    $query .= " AND ITEM_ID = {$remove_condition['item_id']};";
    $result = mysqli_query($db_link, $query);
    $save_result_info = ["result" => $result];
    if ($result === true) {
        $save_result_info["message"] = "カートからの削除が完了しました。";
    } else {
        $save_result_info["message"] = "SQL実行失敗:".$query;
    }
    return $save_result_info;
}
