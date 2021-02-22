<?php
/**
 * ユーザテーブル(MK_USER_TBL)操作用のファイル
 */

/**
 * ユーザ情報の更新を行う。
 * @param $db_link DBコネクション
 * @param $save_info 更新情報
 * @return 更新情報
 */
function update_user($db_link, $save_info) {
    $date = date('Y-m-d H:i:s');
    $query  = " UPDATE MK_USER_TBL";
    $query .= " SET";
    $query .= "     USER_NAME = '{$save_info['user_name']}',";
    $query .= "     PASSWORD = '{$save_info['password']}',";
    $query .= "     AUTH_KBN = '{$save_info['auth_kbn']}',";
    $query .= "     UPDATE_DATE = '{$date}'";
    $query .= " WHERE ID = {$save_info['id']};";
    $result = mysqli_query($db_link, $query);
    $save_result_info = ["result" => $result];
    if ($result === true) {
        $save_result_info["message"] = "ユーザ情報の更新が完了しました。";
    } else {
        $save_result_info["message"] = "SQL実行失敗:".$query;
    }
    return $save_result_info;
}

/**
 * ユーザIDを元にユーザ情報を取得する。
 * @param $db_link DBコネクション
 * @param $user_id ユーザID
 * @return ユーザ情報(単一)
 */
function find_user_by_id($db_link, $user_id) {
    $query  = " SELECT ";
    $query .= "     ID,";
    $query .= "     USER_NAME,";
    $query .= "     PASSWORD,";
    $query .= "     AUTH_KBN,";
    $query .= "     CREATED_DATE,";
    $query .= "     UPDATE_DATE";
    $query .= " FROM MK_USER_TBL";
    $query .= " WHERE ID = {$user_id};";
    // SQL実行
    $result = mysqli_query($db_link, $query);
    // 検索結果の設定
    $retMap = [];
    while ($row = mysqli_fetch_array($result)) {
        $retMap["id"] = $row["ID"];
        $retMap["user_name"] = $row["USER_NAME"];
        $retMap["password"] = $row["PASSWORD"];
        $retMap["auth_kbn"] = $row["AUTH_KBN"];
        $retMap["created_date"] = $row["CREATED_DATE"];
        $retMap["update_date"] = $row["UPDATE_DATE"];
    }
    // メモリのクリア
    mysqli_free_result($result);
    return $retMap;
}

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
