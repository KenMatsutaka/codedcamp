<?php
/**
 * ログアウトコントローラー
 */

// 共通関数の読み込み
require_once "./common_controller.php";

// メイン処理 ==========
execMainAction(function ($db_link) {
    // セッション削除
    unset($_SESSION["user_info"]);
    if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    session_destroy();
    // ログイン画面へ遷移
    header("Location: login_controller.php");
}, false);
