<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>ログイン画面</title>
    <script>
        $(function (){
            // イベントの設定 ==========
            $("#login").click(function () {
                setActionKind($("#loginForm"), this.id);
                $("#loginForm").submit();
            });
        });
    </script>
</head>
<body>
    <form id = "loginForm" action="./login_controller.php" method="POST">
        <div>
            ユーザ名 : <input type="text" name="user_name">
        </div>
        <div>
            パスワード : <input type="password" name="password">
        </div>
        <div>
            <input type="button" id="login" value="ログイン">
        </div>
    </form>
</body>
</html>