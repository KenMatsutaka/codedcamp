<?php
/**
 * ログインコントローラー
 * URL:http://localhost/codecamp/25_1_EC/login_controller.php
 */

// 共通関数読み込み ==========
require_once "./common_controller.php";
require_once "./model/mk_user_tbl.php";

//画面入力情報 ==========
// ユーザ名
$user_name = "";
// パスワード
$password = "";

// メイン処理 ==========
execMainAction(function ($db_link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
        }
        //[ログイン]ボタン押下時
        if ($action_kind === "login") {
            // ユーザ名
            if (isset($_POST["user_name"])) {
                $user_name = $_POST["user_name"];
            }
            // パスワード
            if (isset($_POST["password"])) {
                $password = $_POST["password"];
            }
            // FIXME 入力チェック

            // 認証処理実行
            $user_info_list = find_user_info($db_link, $user_name, $password);
            if(count($user_info_list) > 0) {
                // セッション格納
                $_SESSION["user_info"] = $user_info_list[0];
                // 次画面遷移
                header("Location: item_list_controller.php");
            }
        }
    }
}, false);
// 画面固有の関数 ==========

// 画面読込み
require_once "./view/login.php";
