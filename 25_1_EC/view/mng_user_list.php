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
            $("#search_user").click(function () {
                var formObj = $("#search_uesr_form");
                setActionKind(formObj, this.id);
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
                    <input type="radio" id="general_user" name="auth_kbn" <?php print $auth_kbn === "20" ? "checked" : ""; ?>value="20">一般ユーザのみ
                </label>
            </div>
            <div>
                <input type="button" id="search_user" name="search_user" value="検索">
            </div>
        </form>
    </section>
    <section>
        <h2>ユーザ一覧</h2>
        <input type="button" id="create_new_user" name="create_new_user" value="新規作成">
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
                        <input type="button" name="show_user" value="詳細">
                        <input type="button" name="delete_user" value="削除">
                    </td>
                <tr>
            <?php }?>
        </table>
    </section>
</body>
</html>