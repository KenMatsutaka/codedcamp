<?php
/**
 * カートコントローラー
 */

// 共通関数読み込み ==========
require_once "./common_controller.php";
require_once "./model/mk_cart_tbl.php";
//画面出力情報 ==========
// 合計金額
$total_price = 0;
// カート一覧情報
$cart_item_list = [];

// メイン処理 ==========
execMainAction(function ($db_link) {
    global $total_price, $cart_item_list;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action_kind = null;
        if (isset($_POST["action_kind"])) {
            $action_kind = $_POST["action_kind"];
        }
    }
    // 合計金額
    $total_price = sum_cart_price($db_link, $_SESSION["user_info"]["id"]);
    // 一覧情報取得
    $cart_item_list = find_cart($db_link, $_SESSION["user_info"]["id"]);
});
// 画面固有の関数 ==========

// 画面読込み ==========
require_once "./view/cart.php";
