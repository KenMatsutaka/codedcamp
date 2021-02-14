<?php
/**
 * 商品画面コントローラー
 * URL:http://localhost/codecamp/25_1_EC/item_list_controller.php
 */

// 共通関数読み込み ==========
require_once "./common_controller.php";
require_once "./model/mk_item_tbl.php";

//画面出力情報 ==========
$open_item_list = [];

// メイン処理 ==========
execMainAction(function ($db_link) {
    global $open_item_list;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    }
    $open_item_list = find_open_items($db_link);
});

// 画面固有の関数 ==========
// 画面読込み
require_once "./view/item_list.php";
