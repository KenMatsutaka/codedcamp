<?php
/**
 * カートテーブル(MK_CART_TBL)操作用のファイル
 */

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
