<?php
$filename = 'bbs.txt';
$name = '';
$comment = '';
$error = [];
$data = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //if ($_POST['name'] === TRUE) {
        $name = $_POST['name'];
    //}
    //if (isset($_POST['comment']) === TRUE) {
        $comment = $_POST['comment'];
    //}
    
    if (mb_strlen($name) === 0 || $name === '') {
        $error[] = '名前を入力してください'."\n" ;
    }
    
    if (mb_strlen($name) > 20) {
      $error[] = '名前は20文字以内で書いてください'."\n";
    }
    
    if (mb_strlen($comment) === 0 || $comment === ' ') {
        $error[] = 'コメントを入力してください'."\n";
    }
    
    if (mb_strlen($comment) > 100) {
      $error[] = 'コメントは100文字以内で書いてください';
    }
    
    if (count($error) === 0){
        $info = $name.': '.$comment.' -'.date('Y-m-d H:i:s')."\n";
        if (($file = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($file,$info) === FALSE) {
              $error[] = 'ファイルの書き込みでエラーが発生';
            }
            fclose($file);
        }
    }     
            
}

        
if (($file = fopen($filename,'r')) !== FALSE) {
    while (($line = fgets($file)) !== FALSE) {
        $data[] = htmlspecialchars($line,ENT_QUOTES,'UTF-8');
    }
    fclose($file);
}
    
?>
    



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>10-1</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
<?php foreach($error as $message) { ?>   
    <p><?php print $message ?></p>
<?php } ?>    
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
