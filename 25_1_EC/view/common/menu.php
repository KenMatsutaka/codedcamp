<div>
    <span><?php print htmlspecialchars($_SESSION["user_info"]["user_name"], ENT_QUOTES, 'UTF-8');?>さん こんにちは</span>
    <span><a href="./logout_controller.php">ログアウト</a></span>
</div>
<div>
    <?php if ($_SESSION["user_info"]["auth_kbn"] === "10") {?>
        <a href="./mgn_user_list_controller.php">管理ユーザ一覧</a>
        <a href="./mng_item_list_controller.php">管理商品一覧</a>
    <?php }?>
    <a href="./item_list_controller.php">商品リスト</a>
</div>
