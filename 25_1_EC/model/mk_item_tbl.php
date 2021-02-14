<?php
/**
 * 商品テーブル(MK_ITEM_TBL)操作用のファイル
 */

/**
 * 公開中の商品情報を取得する。
 * @param $db_link DBコネクション
 * @return 商品情報
 */
function find_open_items($db_link) {
    $retList = [];
    $query  = " SELECT ";
    $query .= "   it.ID,";
    $query .= "   it.NAME,";
    $query .= "   it.PRICE,";
    $query .= "   it.IMG,";
    $query .= "   it.STATUS,";
    $query .= "   ist.STOCK";
    $query .= " FROM MK_ITEM_TBL it";
    $query .= " INNER JOIN MK_ITEM_STOCK_TBL ist";
    $query .= " ON it.ID = ist.ITEM_ID";
    $query .= " WHERE it.STATUS = '1'";
    $query .= " ORDER BY it.ID ASC;";
    $result = mysqli_query($db_link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["id"] = $row["ID"];
        $rowMap["name"] = $row["NAME"];
        $rowMap["price"] = $row["PRICE"];
        $rowMap["img"] = $row["IMG"];
        $rowMap["status"] = $row["STATUS"];
        $rowMap["stock"] = $row["STOCK"];
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList;
}
