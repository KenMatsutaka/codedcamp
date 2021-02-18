<?php
/**
 * ログインコントローラー
 * URL:http://localhost/codecamp/25_1_EC/login_controller.php
 */

// 共通関数読み込み ==========
require_once "./common_controller.php";
require_once "./model/mk_cart_tbl.php";
require_once "./model/mk_buy_item_tbl.php";
require_once "./model/mk_item_stock_tbl.php";

// 購入商品一覧
$buy_item_list = [];

// メイン処理 ==========
execMainAction(function ($db_link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
            if($action_kind === "buy_item") {
                exec_buy_item($db_link);
            }
        }
    }
});

// 画面固有の関数 ==========
/**
 * 購入処理を実行する。
 * @db_link DBコネクション
 */
function exec_buy_item($db_link) {
    global $buy_item_list;
    global $error_messages;
    mysqli_autocommit($db_link, false);
    // カート情報の取得
    $cart_info_list = find_cart($db_link, $_SESSION["user_info"]["id"]);
    // 商品コード採番
    $buy_code = numbering_buy_code($db_link);
    // 購入テーブルへの登録
    $save_result = save_buy_item($db_link, $_SESSION["user_info"]["id"], $buy_code, $cart_info_list);
    if ($save_result["result"]) {
        // 商品在庫情報の更新
        $update_result = update_item_stock($db_link, $cart_info_list);
        if ($update_result["result"]) {
            // カート情報の削除
            $remove_result = remove_cart($db_link, ["user_id" => $_SESSION["user_info"]["id"]]);
            if ($remove_result["result"]) {
                // 購入一覧情報の取得
                $buy_item_list = find_buy_item_list($db_link, $buy_code);
            } else {
                $error_messages[] = $remove_result["message"];
            }
        } else {
            $error_messages[] = $update_result["message"];
        }
    } else {
        $error_messages[] = $save_result["message"];
    }
    if (count($error_messages) === 0) {
        mysqli_commit($db_link);
    } else {
        mysqli_rollback($db_link);
    }
}
// 画面読込み
require_once "./view/buy_item.php";
