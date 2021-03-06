<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>管理ユーザ詳細画面</title>
    <style>
    </style>
    <script>
        $(function (){
            // [更新]ボタン押下時
            $("#edit_user_btn").click(function () {
                var formObj = $("#save_user_form");
                setActionKind(formObj, this.name);
                formObj.submit();
            });
            // [新規作成]ボタン押下時
            $("#create_user_btn").click(function () {
                var formObj = $("#save_user_form");
                setActionKind(formObj, this.name);
                formObj.submit();
            });
        });
    </script>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <?php if (is_detail()) {?>
        <h1>管理ユーザ詳細画面</h1>
    <?php } else if (is_create()) {?>
        <h1>管理ユーザ新規作成画面</h1>
    <?php }?>
    <?php require_once "./view/common/message.php";?>
    <form id="save_user_form" method="POST">
        <?php if (is_detail()) {?>
            <div>
                ユーザID : <?php print $user["id"]; ?>
                <input type="hidden" name="id" value="<?php print $user["id"]; ?>">
            </div>
        <?php }?>
        <div>
            ユーザ名 : <input type="text" name="user_name" value="<?php print $user["user_name"]; ?>">
        </div>
        <div>
            パスワード : <input type="text" name="password" value="<?php print $user["password"]; ?>">
        </div>
        <div>
            権限 : 
            <label for="manage_user">
                <input type="radio" id="manage_user" name="auth_kbn" <?php print $user["auth_kbn"] === "10" ? "checked" : ""; ?> value="10">管理ユーザ
            </label>
            <label for="general_user">
                <input type="radio" id="general_user" name="auth_kbn" <?php print $user["auth_kbn"] === "20" ? "checked" : ""; ?> value="20">一般ユーザ
            </label>
        </div>
        <?php if (is_detail()) {?>
            <div>
                登録日 : <?php print $user["created_date"];?>
                <input type="hidden" name="created_date" value="<?php print $user['created_date'];?>">
            </div>
            <div>
                更新日 : <?php print $user["update_date"];?>
                <input type="hidden" name="update_date" value="<?php print $uesr['update_date'];?>">
            </div>
        <?php }?>
        <div>
            <?php if (is_detail()) {?>
                <input type="button" id="edit_user_btn" name="edit_user" value="更新">
            <?php } else if (is_create()) {?>
                <input type="button" id="create_user_btn" name="exec_create_user" value="新規作成">
            <?php }?>
        </div>
    </form>
</body>
</html>