<?php
/**
 * 商品一覧コントローラー
 * URL:http://localhost/codecamp/25_1_EC/item_list_controller.php
 */

// 共通関数読み込み ==========
require_once "./common_controller.php";
require_once "./model/mk_item_tbl.php";
require_once "./model/mk_cart_tbl.php";

//画面出力情報 ==========
$open_item_list = [];

// メイン処理 ==========
execMainAction(function ($db_link) {
    global $open_item_list,$success_message,$error_messages;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // システム日付
        $date = date('Y-m-d H:i:s');
        $action_kind = null;
        if(isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
        }
        $item_id = null;
        if(isset($_POST["item_id"])) {
            $item_id = $_POST["item_id"];
        }
        // [カートに追加]ボタン押下時
        if ($action_kind === "add_cart") {
            $save_info = [
                "user_id" => $_SESSION["user_info"]["id"],
                "item_id" => $item_id,
                "amount" => 1,
                "created_date" => $date,
                "update_date" => $date
            ];
            $save_result = add_cart($db_link, $save_info);
            if ($save_result["result"]) {
                $success_message = $save_result["message"];
            } else {
                $error_messages[] = $save_result["message"];
            }
        // [カートから削除]ボタン押下時
        } else if($action_kind === "remove_cart") {
            $remove_condition = [
                "user_id" => $_SESSION["user_info"]["id"] ,
                "item_id" => $item_id
            ];
            $save_result = remove_cart($db_link, $remove_condition);
            if ($save_result["result"]) {
                $success_message = $save_result["message"];
            } else {
                $error_messages[] = $save_result["message"];
            }
        }
    }
    $open_item_list = find_open_items($db_link, $_SESSION["user_info"]["id"]);
});

// 画面固有の関数 ==========
// 画面読込み
require_once "./view/item_list.php";
