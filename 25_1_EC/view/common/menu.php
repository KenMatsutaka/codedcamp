<span><?php print htmlspecialchars($_SESSION["user_info"]["user_name"], ENT_QUOTES, 'UTF-8');?>さん こんにちは</span>
<?php if ($_SESSION["user_info"]["auth_kbn"] === "10") {?>
    <a href="./manage_user_list_controller.php">管理ユーザ一覧</a>
    <a href="./manage_item_list_controller.php">管理商品一覧</a>
<?php }?>
<span><a href="./logout_controller.php">ログアウト</a></span>