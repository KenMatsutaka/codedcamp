<?php
/**
 * 購入テーブル(MY_BUY_ITEM_TBL)操作用のファイル
 */

/**
 * 購入情報の登録を行う。
 * @param $db_link DBコネクション
 * @param $user_id　ユーザID
 * @param $save_info_list 登録情報リスト
 * @return 登録結果
 */
function save_buy_item($db_link, $user_id, $save_info_list) {
    //システム日付
    $date = date('Y-m-d H:i:s');
    // 商品コード取得
    // FIXME 商品コードの取得は別だしにする
    $query = "SELECT IFNULL(MAX(BUY_CODE),0)+1 SEQ FROM MK_BUY_ITEM_TBL;";
    $result = mysqli_query($db_link, $query);
    $buy_code = 0;
    while ($row = mysqli_fetch_array($result)) {
        $buy_code = $row["SEQ"];
    }
    // 購入情報登録
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
