<?php
/**
 * 管理ユーザ一覧コントローラー
 */

// 共通関数の読込み ==========
require_once "./common_controller.php";
require_once "./model/mk_user_tbl.php";

// 画面で使用する項目
// 処理種別
$action_kind = "";
// ユーザ情報
$user = ["user_name" => "", "password" => "", "auth_kbn" => "10"];

// メイン処理 ==========
execMainAction(function ($db_link) {
    global $action_kind, $user;
    global $success_message, $error_messages;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
        }
        // 詳細表示時
        if ($action_kind === "user_detail") {
            $user_id = null;
            if (isset($_POST["user_id"])) {
                $user_id = $_POST["user_id"];
            }
            $user = find_user_by_id($db_link, $user_id);
        // [更新]ボタン押下時
        } else if ($action_kind === "edit_user") {
            // ユーザID
            if (isset($_POST["id"])) {
                $user["id"] = $_POST["id"];
            }
            // ユーザ名
            if (isset($_POST["user_name"])) {
                $user["user_name"] = $_POST["user_name"];
            }
            // パスワード
            if (isset($_POST["password"])) {
                $user["password"] = $_POST["password"];
            }
            // 権限
            if (isset($_POST["auth_kbn"])) {
                $user["auth_kbn"] = $_POST["auth_kbn"];
            }
            // 登録日
            if (isset($_POST["created_date"])) {
                $user["created_date"] = $_POST["created_date"];
            }
            // 更新日
            if( (isset($_POST["update_date"]))) {
                $user["update_date"] = $_POST["update_date"];
            }
            $save_result = update_user($db_link, $user);
            if ($save_result["result"]) {
                $user = find_user_by_id($db_link, $user["id"]);
                $success_message = $save_result["message"];
            } else {
                $error_messages[] = $save_result["message"];
            }
        // [新規作成]ボタン押下時
        } else if ($action_kind === "exec_create_user") {
            // ユーザ名
            if (isset($_POST["user_name"])) {
                $user["user_name"] = $_POST["user_name"];
            }
            // パスワード
            if (isset($_POST["password"])) {
                $user["password"] = $_POST["password"];
            }
            // 権限
            if (isset($_POST["auth_kbn"])) {
                $user["auth_kbn"] = $_POST["auth_kbn"];
            }
            // ユーザ名重複チェック
            if (check_duplicate_user_name($db_link, $user["user_name"])) {
                print "ユーザ登録処理実行";
            } else {
                $error_messages[] = "ユーザ名が重複しています。";
            }
        }
    }
});

// 画面固有の関数 ==========
/**
 * ユーザ名の重複チェックを行う
 * @param $db_link DBコネクション
 * @param $user_name ユーザ名
 * @return チェック結果 true:重複なし false:重複あり
 */
function check_duplicate_user_name($db_link, $user_name) {
    $ret_flag = false;
    $count = count_user($db_link, $user_name);
    if ($count === "0") {
        $ret_flag = true;
    }
    return $ret_flag;
}

/**
 * 詳細画面かどうかを判定する
 * @return 判定結果 true:詳細画面 false:詳細画面以外
 */
function is_detail() {
    global $action_kind;
    $ret_flag = false;
    if ($action_kind === "user_detail" || $action_kind === "edit_user") {
        $ret_flag = true;
    }
    return $ret_flag;
}

/**
 * 新規作成画面かどうかを判定する
 * @return 判定結果 true:新規作成画面 false:新規作成画面以外
 */
function is_create() {
    global $action_kind;
    $ret_flag = false;
    if ($action_kind === "create_user" || $action_kind === "exec_create_user") {
        $ret_flag = true;
    }
    return $ret_flag;
}

// 画面読込み ==========
require_once "./view/mng_user_detail.php";