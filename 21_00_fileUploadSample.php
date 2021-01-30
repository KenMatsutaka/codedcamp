<?php
/**
 * 提出課題：自動販売機
 * ファイルアップロードサンプル
 * URL:http://localhost:80/codecamp/21_00_fileUploadSample.php
 */
$fileNames = [];
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ↓アップロードしたファイルの情報はスーパーグローバル変数「$_FILES」の中にある
  if (is_uploaded_file($_FILES['uploadFile']['tmp_name'])) {
    // $_FILES[【パラメータ名】]['tmp_name']にアップロードファイルがある
    // 一時的に格納されているのでそれを任意の場所に移動する
    move_uploaded_file($_FILES['uploadFile']['tmp_name'], "./img/".$_FILES['uploadFile']['name']);
  }
}
// ./imgディレクトリ内のファイル情報を表示
$fileNames = findFileNames("./img");

/**
 * 指定されたディレクトリにあるファイル名一覧を取得
 * @param $dir ディレクトリ
 * @return ファイル名一覧
 */
function findFileNames($dir) {
  $fileNames = [];
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
          // ディレクトリ(フォルダ)は除外
          if(filetype($dir."/".$file) !== "dir") {
            $fileNames[] = $file;
          }
        }
        closedir($dh);
    }
  }
  return $fileNames;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイルアップロードサンプル</title>
</head>
<body>
  <section>
    <!-- formタグにenctype="multipart/form-data"を追加 -->
    <form method="POST" enctype="multipart/form-data">
      <!-- inputタグのtype="file"を作成 -->
      <input type="file" name="uploadFile" value="ファイル選択" /><br>
      <br>
      <input type="submit" value="アップロード" />
    </form>
  </section>
  <hr>
  <section>
    <p>画像一覧情報</p>
    <?php foreach($fileNames as $fileName) { ?>
    <img src="./img/<?php print $fileName?>" height="100px">
    <br>
    <?php } ?>
  </section>

</body>
</html>