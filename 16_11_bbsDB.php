<?php
/**
 * 課題番号：16-11
 * ひとこと掲示板をDBを使用して作成する。
 * 接続先URL：http://localhost:80/codecamp/16_11_bbsDB.php
 */
// 入力項目 --------------
// 名前
$name = "";
// コメント
$comment = "";

// 出力項目 --------------
// エラーメッセージ
$errorMessages = [];
// 一覧表示項目
$commentList = [];

// メイン処理 開始 ------------
// DBコネクション取得
$link = getDBLink();
//入力項目が存在する場合、登録処理を行う。
if (isset($_POST["name"]) && isset($_POST["comment"])) {
  // 入力チェックOKの場合登録処理を実行
  if (checkInputValue()) {
    // 登録処理
    saveComment($link, $name, $comment);
  }
}
// コメント情報を取得
$commentList = findComment($link);
// DBコネクションクローズ
mysqli_close($link);
// メイン処理 終了 ------------
/**
 * 入力項目のチェック処理を行う。
 * @return 判定結果 true:OK false:NG
 */
function checkInputValue() {
  global $name,$comment,$errorMessages;
  // ----- 名前 -----
  $name = $_POST["name"];
  // 必須チェック
  if (!checkNotEmpty($name)) {
    $errorMessages[] = "名前は必須です。";
  }
  // 桁数チェック
  if (!checkMaxlength($name, 20)) {
    $errorMessages[] = "名前は20文字以内で入力してください。";
  }
  // ----- コメント -----
  $comment = $_POST["comment"];
  // 必須チェック
  if (!checkNotEmpty($comment)) {
    $errorMessages[] = "コメントは必須です。";
  }
  // 桁数チェック
  if (!checkMaxlength($comment, 100)) {
    $errorMessages[] = "コメントは100文字以内で入力してください。";
  }
  // チェック結果の判定
  $retFlag = false;
  if (count($errorMessages) === 0) {
    $retFlag = true;
  }
  return $retFlag;
}

/**
 * コメント情報を取得する。
 * @param データベースコネクション
 * @retrurn コメント情報リスト
 */
function findComment($link) {
  $retList = [];
  $query = "SELECT "
          ."  COMMENT_ID,"
          ."  NAME,"
          ."  COMMENT,"
          ."  INS_DATE_TIME "
          ."FROM COMMENT_TBL "
          ."ORDER BY INS_DATE_TIME ASC;";
  // クエリを実行します。
  $result = mysqli_query($link, $query);
  while ($row = mysqli_fetch_array($result)) {
    $rowMap = [];
    $rowMap["name"] = $row["NAME"];
    $rowMap["comment"] = $row["COMMENT"];
    $rowMap["insDateTime"] = $row["INS_DATE_TIME"];
    $retList[] = $rowMap;
  }
  // メモリのクリア
  mysqli_free_result($result);
  return $retList;
}

/**
 * コメント情報を登録する。
 * @param $link データベースコネクション
 * @param $name 名前
 * @param $comment コメント
 */
function saveComment($link, $name, $comment) {
  //SQLインジェクションが気になるが一旦放置
  $query = "INSERT INTO COMMENT_TBL "
          ."(NAME,COMMENT,INS_DATE_TIME,UPD_DATE_TIME)"
          ." VALUES ('".$name."', '".$comment."', NOW(), NOW());";
  mysqli_query($link, $query);
}

// ユーティリティ ==========
/**
 * 必須チェックを行う
 * @param $value 入力値
 * @return 判定結果 true:空文字・null以外 false:空文字またはnull
 */
function checkNotEmpty($value) {
  $retFlag = false;
  if ($value !== null && $value !== "") {
    $retFlag = true;
  }
  return $retFlag;
}

/**
 * 最大文字数以下である事をチェックする。
 * @param $value 入力値
 * @param $maxLength 最大文字数
 * @return 判定結果 true:最大文字数以内 false:最大文字数より大きい
 */
function checkMaxlength($value, $maxLength) {
  $retFlag = false;
  if (mb_strlen($value) <= $maxLength) {
    $retFlag = true;
  }
  return $retFlag;
}

/**
 * データベースコネクションを取得する。
 * @return データベースコネクション
 */
function getDBLink() {
  // DBの接続情報
  $host = "localhost";
  $userName = "root";
  $passWord = "";
  $dbName = "codecampdb";
  $link = mysqli_connect($host, $userName, $passWord, $dbName);
  // 接続状況をチェックします
  if (mysqli_connect_errno()) {
      die("データベースに接続できません:" . mysqli_connect_error() . "\n");
  }
  return $link;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>16-11:ひとこと掲示板</title>
  <style>
    .errorMessage {
      color: #FF0000;
    }
  </style>
</head>
<body>
  <?php foreach($errorMessages as $errorMessage) { ?>
    <p class="errorMessage"><?php print $errorMessage ?></p>
  <?php } ?>
  <h1>ひとこと掲示板</h1>
  <form method="post">
    <label for="name">名前: <input type="text" name="name" value="<?php print $name?>"></label>
    <label for="comment">コメント: <input type="text" name="comment" value="<?php print $comment?>"></label>
    <input type="submit" value="送信">
  </form>
  <ul>
  <?php foreach($commentList as $rowMap) { ?>
    <li><?php print htmlspecialchars($rowMap["name"].": ".$rowMap["comment"]." -".$rowMap["insDateTime"]); ?></li>
  <?php } ?>
  </ul>
</body>