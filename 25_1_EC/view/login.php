<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>ログイン画面</title>
    <script>
        $(function (){
            // イベントの設定 ==========
            $("#login").click(function () {
                $("#loginForm").find("[name='action_kind']").val(this.id);
                $("#loginForm").submit();
            });
        });
    </script>
</head>
<body>
    <form id = "loginForm" action="./login_controller.php" method="POST">
        <div>
            ログインID : <input type="text" name="login_id">
        </div>
        <div>
            パスワード : <input type="text" name="login_password">
        </div>
        <div>
            <input type="button" id="login" value="ログイン">
        </div>
    </form>
</body>
</html>