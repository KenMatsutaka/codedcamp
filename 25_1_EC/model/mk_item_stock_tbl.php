<?php
/**
 * 在庫テーブル(ITEMa_stock_TBL)操作用のファイル
 */

/**
 * 在庫情報の更新を行います。
 * @param $db_link DBコネクション
 * @param $save_info_list 更新情報リスト
 * @return 更新結果
 */
function update_item_stock($db_link, $update_info_list) {
    //システム日付
    $date = date('Y-m-d H:i:s');
    $error_flag = false;
    foreach($update_info_list as $update_info) {
        $update_query  = "UPDATE MK_ITEM_STOCK_TBL ";
        $update_query .= "SET STOCK = STOCK - {$update_info['amount']}, UPDATE_DATE = '{$date}'";
        $update_query .= "WHERE ITEM_ID = {$update_info['item_id']}";
        $result = mysqli_query($db_link, $update_query);
        if ($result === false) {
            $error_flag = true;
            break;
        }
    }
    $update_result_info = [];
    if ($error_flag === false) {
        $update_result_info["result"] = true;
        $update_result_info["message"] = "在庫の更新が完了しました。";
    } else {
        $update_result_info["result"] = false;
        $update_result_info["message"] = "SQL実行失敗:".$update_query;
    }
    return $update_result_info;

}
