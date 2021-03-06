<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>管理ユーザ一覧画面</title>
    <style>
        section {
            margin-bottom: 20px;
            border-top: solid 1px;
        }
        table {
            width: 900px;
            border-collapse: collapse;
        }
        table, tr, th, td {
            border: solid 1px;
            padding: 5px;
            text-align: center;
        }
    </style>
    <script>
        $(function (){
            // イベント設定 ----------
            // [検索]ボタン押下時の処理
            $("#search_user").click(function () {
                var formObj = $("#search_uesr_form");
                setActionKind(formObj, this.id);
                formObj.submit();
            });
            // [新規作成]ボタン押下時の処理
            $("#create_user_btn").click(function () {
                var formObj = $("#user_detail_form");
                setActionKind(formObj, this.name);
                $("#detail_user_id").val("");
                formObj.submit();
            });
            // [詳細]ボタン押下時の処理
            $(".user_detail_btn").click(function () {
                var formObj = $("#user_detail_form");
                setActionKind(formObj, this.name);
                $("#detail_user_id").val(this.id.split("_")[2]);
                formObj.submit();
            });
            // [削除]ボタン押下時の処理
            $(".delete_user_btn").click(function () {
                var formObj = $("#delete_user_form");
                setActionKind(formObj, this.name);
                $("#delete_user_id").val(this.id.sprintf("_"[2]));
                formObj.submit();
            });
        });
    </script>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <h1>管理ユーザ一覧画面</h1>
    <?php require_once "./view/common/message.php";?>
    <section>
        <h2>ユーザ検索</h2>
        <form id="search_uesr_form" method="POST">
            <div>ユーザ名 : <input type="text" name="user_name" value="<?php print $user_name;?>"></div>
            <div>
                権限 : 
                <label for="no_select">
                    <input type="radio" id="no_select" name="auth_kbn" <?php print $auth_kbn === "" ? "checked" : ""; ?> value="">指定なし
                </label>
                <label for="manage_user">
                    <input type="radio" id="manage_user" name="auth_kbn" <?php print $auth_kbn === "10" ? "checked" : ""; ?> value="10">管理ユーザのみ
                </label>
                <label for="general_user">
                    <input type="radio" id="general_user" name="auth_kbn" <?php print $auth_kbn === "20" ? "checked" : ""; ?> value="20">一般ユーザのみ
                </label>
            </div>
            <div>
                <input type="button" id="search_user" name="search_user" value="検索">
            </div>
        </form>
    </section>
    <section>
        <h2>ユーザ一覧</h2>
        <form id="user_detail_form" method="POST" action="./mng_user_detail_controller.php">
            <input type="hidden" id="detail_user_id" name="user_id" value="">
            <input type="hidden" id="exec_kbn" name="exec_kbn" value="">
        </form>
        <form id="delete_user_form" method="POST">
            <input type="hidden" id="delete_user_id" name="user_id" value="">
        </form>
        <input type="button" id="create_user_btn" name="create_user" value="新規作成">
        <table>
            <tr>
                <th>ユーザID</th>
                <th>ユーザ名</th>
                <th>パスワード</th>
                <th>権限区分</th>
                <th>操作</th>
            </tr>
            <?php foreach($user_list as $user) {?>
                <tr>
                    <td><?php print $user["id"]; ?></td>
                    <td><?php print htmlspecialchars($user["user_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($user["password"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php htmlspecialchars(print AUTH_KBN[$user["auth_kbn"]], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <input type="button" class="user_detail_btn" id="user_detail_<?php print $user['id'];?>" name="user_detail" value="詳細">
                        <input type="button" class="delete_user_btn" id="delete_user_<?php print $user['id'];?>" name="delete_user" value="削除">
                    </td>
                <tr>
            <?php }?>
        </table>
    </section>
</body>
</html>