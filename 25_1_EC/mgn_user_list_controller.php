<?php
/**
 * 管理ユーザ一覧コントローラー
 */

// 共通関数の読込み ==========
require_once "./common_controller.php";
require_once "./model/mk_user_tbl.php";

// 画面入力項目 ==========
// 検索項目 ----------
// ユーザ名
$user_name = "";
// 権限
$auth_kbn = "";

// 画面出力項目 ==========
// ユーザ一覧情報
$user_list = [];

// メイン処理 ==========
execMainAction(function ($db_link) {
    global $user_name, $auth_kbn;
    global $user_list;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
            // [検索]ボタン押下時
            if ($action_kind === "search_user") {
                // ユーザ名 
                if (isset($_POST["user_name"])) {
                    $user_name = $_POST["user_name"];
                }
                // 権限
                if (isset($_POST["auth_kbn"])) {
                    $auth_kbn = $_POST["auth_kbn"];
                }
            }
        }
    }
    // 検索処理実行
    $search_condition = ["user_name" => $user_name, "auth_kbn" => $auth_kbn];
    $user_list = find_user_list($db_link, $search_condition);
});

// 画面固有の関数 ==========

// 画面読込み ==========
require_once "./view/mng_user_list.php";

