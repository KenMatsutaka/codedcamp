<?php
date_default_timezone_set('Asia/Tokyo');
$filename = './challenge_log.txt';
$comment = '';

//連想配列
//hoge["key"] = "value";

// 名前　name = name
// 年齢 name = age
// $_POST["name"] //まつけん
// $_POST["age"] //43



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment']) === TRUE) {
        $comment = $_POST['comment'];
    }
    if (($fp = fopen($filename, 'a')) !== FALSE) {
        $message = date("Y/m/d H:i:s")." ".$comment."\n";
        if (fwrite($fp,$message) === FALSE) {
            print 'ファイル書き込み失敗:  ' . $filename;
        }
        fclose($fp);
    }
}

$data = [];

//　is_readable : true,false
if (is_readable($filename) === TRUE) {
    if (($fp =  fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES,'UTF-8');
        }
        fclose($fp);
    } else {
        $data[] = 'ファイルがありません';
    }
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>9-10</title>
</head>
<body>
    <h1>課題</h1>
    <form method="post">
        <label for="comment">発言:<input type="text" name="comment"></label>
        <input type="submit" value="送信">
        <P>発言一覧</P>
<?php foreach($data as $value) { ?>
        <P><?php print $value; ?></P>
<?php } ?>
    </form>
</body>
</html>