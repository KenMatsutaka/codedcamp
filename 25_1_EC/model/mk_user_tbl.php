<?php
/**
 * ユーザテーブル(MK_USER_TBL)操作用のファイル
 */

/**
 * ユーザ情報を取得する。
 * @param $db_link DBコネクション
 * @param $search_condition 検索条件
 * @return ユーザ情報
 */

function find_user_list($db_link, $search_condition) {
    $retList = [];
    $query  = " SELECT ";
    $query .= "   ID,";
    $query .= "   USER_NAME,";
    $query .= "   PASSWORD,";
    $query .= "   AUTH_KBN,";
    $query .= "   CREATED_DATE,";
    $query .= "   UPDATE_DATE";
    $query .= " FROM MK_USER_TBL";
    // 検索条件の設定
    $first_flag = true;
    $where = "";
    // ユーザ名
    if (isset($search_condition["user_name"])) {
        if ($search_condition["user_name"] !== "") {
            $where .= $first_flag === true ? " WHERE" : " AND";
            $first_flag = false;
            $where .= " USER_NAME LIKE '%{$search_condition['user_name']}%'";
        }
    }
    // 権限
    if (isset($search_condition["auth_kbn"])) {
        if ($search_condition["auth_kbn"] !== "") {
            $where .= $first_flag === true ? " WHERE" : " AND";
            $first_flag = false;
            $where .= " AUTH_KBN = '{$search_condition['auth_kbn']}'";
        }
    }
    $query .= $where;
    // ソート条件
    $query .= " ORDER BY ID ASC";
    // SQL実行
    $result = mysqli_query($db_link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["id"] = $row["ID"];
        $rowMap["user_name"] = $row["USER_NAME"];
        $rowMap["password"] = $row["PASSWORD"];
        $rowMap["auth_kbn"] = $row["AUTH_KBN"];
        $rowMap["created_date"] = $row["CREATED_DATE"];
        $rowMap["update_date"] = $row["UPDATE_DATE"];
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList;
}


/**
 * ユーザ名とパスワードを元に該当するユーザ情報を取得する。
 * @param $user_name ユーザ名
 * @param $password パスワード
 * @return ユーザ情報
 */
function find_user_for_login($db_link, $user_name, $password) {
    $retList = [];
    $query  = " SELECT ";
    $query .= "   ID,";
    $query .= "   USER_NAME,";
    $query .= "   PASSWORD,";
    $query .= "   AUTH_KBN,";
    $query .= "   CREATED_DATE,";
    $query .= "   UPDATE_DATE";
    $query .= " FROM MK_USER_TBL";
    $query .= " WHERE USER_NAME = '{$user_name}'";
    $query .= " AND   PASSWORD = '{$password}'";
    $result = mysqli_query($db_link, $query);
    // 検索結果の設定
    while ($row = mysqli_fetch_array($result)) {
        $rowMap = [];
        $rowMap["id"] = $row["ID"];
        $rowMap["user_name"] = $row["USER_NAME"];
        $rowMap["password"] = $row["PASSWORD"];
        $rowMap["auth_kbn"] = $row["AUTH_KBN"];
        $rowMap["created_date"] = $row["CREATED_DATE"];
        $rowMap["update_date"] = $row["UPDATE_DATE"];
        $retList[] = $rowMap;
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retList;
}
