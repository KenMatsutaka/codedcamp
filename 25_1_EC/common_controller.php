<?php
require_once "./model/common/db_connection.php";

// 成功メッセージ
$success_message = "";
// エラーメッセージ
$error_messages = [];

/**
 * サーバーサイドの処理を実行する。
 * @param $mainFunc コールバック関数
 * @param $check_session セッションチェック実施有無
 */
function execMainAction($main_func, $check_session = true) {
    session_start();
    if ($check_session) {
        if(!isset($_SESSION["user_info"])) {
                // ログイン画面へ遷移
            header("Location: login_controller.php");
        }
    }
    // DBコネクションの取得
    $db_link = getDBLink();
    $main_func($db_link);
    // DBコネクションクローズ
    mysqli_close($db_link);
}