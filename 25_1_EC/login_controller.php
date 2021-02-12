<?php
/**
 * ログイン画面コントローラー
 * URL:http://localhost/codecamp/25_1_EC/login_controller.php
 */

// 共通関数読み込み ==========
require_once "./controller_common.php";

//画面入力情報 ==========
// ログインID
$login_id;
// パスワード
$login_password;

// メイン処理 ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    execMainAction(function ($db_link) {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
        }
        //[ログイン]ボタン押下時
        if ($action_kind === "login") {

        }
    }, false);
}

// 画面読込み
require_once "./view/login.php";
