<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>管理ユーザ詳細画面</title>
    <style>
    </style>
    <script>
        $(function (){
            $("#edit_user_btn").click(function () {
                var formObj = $("#save_user_form");
                setActionKind(formObj, this.name);
                formObj.submit();
            });
        });
    </script>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <h1>管理ユーザ詳細画面</h1>
    <?php require_once "./view/common/message.php";?>
    <form id="save_user_form" method="POST">
        <div>
            ユーザID : <?php print $user["id"]; ?>
            <input type="hidden" name="id" value="<?php print $user["id"]; ?>">
        </div>
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
        <div>
            登録日 : <?php print $user["created_date"];?>
            <input type="hidden" name="created_date" value="<?php print $user['created_date'];?>">
        </div>
        <div>
            更新日 : <?php print $user["update_date"];?>
            <input type="hidden" name="update_date" value="<?php print $uesr['update_date'];?>">
        </div>
        <div>
            <input type="button" id="edit_user_btn" name="edit_user" value="更新">
        </div>
    </form>
</body>
</html>