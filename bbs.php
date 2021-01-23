<!--できてないところ-->
<!--　１ファイルの中身を全部消去してからこのbbs.php-->
<!--　　のページを更新しても、最後に書き込んだものがなぜか表示される-->
<!--　２利用者の名前は最大20文字、コメントの文字数は最大100文字までで、-->
<!--　　それを超すとエラーメッセージを表示して書き込みできないよにする-->
<!--　３名前もしくはコメントが未入力の場合もエラーメッセージが表示され-->
<!--　　書き込み出来ないよにする-->
<?php
$filename = 'bbs.txt';
$name = '';
$comment = '';
$errorMessage;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) === TRUE) {
        $name = $_POST['name'];
    }
    if (isset($_POST['comment']) === TRUE) {
        $comment = $_POST['comment'];
    }
    $checkFlg = true;
    // 入力チェック
    // ■名前
    // 必須チェック
    if (mb_strlen($name) === 0) {
        $checkFlg = false;
        $errorMessage = '名前は必須です。';
        //print '名前は必須です。';
    }
        // 桁数チェック
    if (mb_strlen($name) > 20) {
        $checkFlg = false;
        $errorMessage = '名前は20文字以内で書いてください。';
        //print '名前は20文字以内で書いてください。';
    }
    // ■コメント
    // 必須チェック
    // 桁数チェック

    if ($checkFlg) {
        $info = $name.': '.$comment.' -'.date('Y-m-d H:i:s')."\n";
        if (($file = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($file,$info) === FALSE) {
                print 'ファイルの書き込みでエラーが発生';
            }
            fclose($file);
        }
    }
    $data = [];
    if (($file = fopen($filename,'r')) !== FALSE) {
        while (($line = fgets($file)) !== FALSE) {
            $data[] = htmlspecialchars($line,ENT_QUOTES,'UTF-8');
        }
        fclose($file);
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>10-1</title>
</head>
<body>
    <div><?php print $errorMessage; ?></div>
    <h1>ひとこと掲示板</h1>
    <form method="post">
        <label for="name">名前: <input type="text" name="name"></label>
        <label for="comment">コメント: <input type="text" name="comment"></label>
        <input type="submit" value="送信">
    </form>
    <ul>
<?php foreach($data as $value) { ?>
    <li><?php print $value; ?></li>
<?php } ?>
    </ul>
</body>
</html>