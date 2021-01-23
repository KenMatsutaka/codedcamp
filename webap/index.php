<?php
/**
 * 画面名：ログイン画面
 * URL:http://localhost/codecamp/webap/index.php
 */
// 画面入力項目
// ユーザID
$userId = "";
// パスワード
$password = "";

// 画面出力項目
$errorMessages = [];

// サーバーサイド処理実行
execAction(function ($dbLink) {
  global $userId, $password;
  global $errorMessages;
  // 入力項目の設定 -----
  // ユーザID
  if (isset($_POST["userId"])) {
    $userId = $_POST["userId"];
  }
  // パスワード
  if (isset($_POST["password"])) {
    $password =  $_POST["password"];
  }
  // 入力チェック処理 -----
  if (validateInputValue()) {
    // 認証処理 -----
    // ユーザ情報の取得
    $resultList = findUser($dbLink, $userId, $password);
    if(count($resultList) === 1) {
      // セッションにユーザ情報を設定
      $_SESSION["userInfo"] = $resultList[0];
      // 次画面へ遷移
      header("Location: menu.php");
    } else {
      // エラーメッセージ表示
      $errorMessages[] = "ユーザIDまたはパスワードに誤りがあります。";
    }
  }
});

/**
 * 入力項目のチェックを行う
 * @return 判定結果 true:エラーなし false:エラーあり
 */
function validateInputValue() {
  global $userId, $password;
  global $errorMessages;
  // ユーザID -----
  // 必須チェック
  if (!ValidateUtil::checkNotEmpty($userId)) {
    $errorMessages[] = "ユーザIDは必須です。";
  }
  // 桁数チェック
  if (!ValidateUtil::checkMaxlength($userId, 20)) {
    $errorMessages[] = "ユーザIDは20文字以下で入力してください。";
  }
  // パスワード -----
  // 必須チェック
  if (!ValidateUtil::checkNotEmpty($password)) {
    $errorMessages[] = "パスワードは必須です。";
  }
  if (!ValidateUtil::checkMaxlength($password, 20)) {
    $errorMessages[] = "パスワードは20文字以下で入力してください。";
  }
  // エラー判定
  $retFlag = false;
  if (count($errorMessages) === 0) {
    $retFlag = true;
  }
  return $retFlag;
}

/**
 * ユーザ情報を取得する。
 * @$dbLink DBコネクション
 * @$userId ユーザID
 * @password パスワード
 */
function findUser($dbLink, $userId, $password) {
  $query = "  SELECT"
          ."    USER_ID,"
          ."    PASSWORD,"
          ."    FAMILY_NAME,"
          ."    FIRST_NAME,"
          ."    INS_DATE_TIME,"
          ."    UPD_DATE_TIME"
          ."  FROM USER_TBL"
          ."  WHERE USER_ID = '".$userId."'"
          ."  AND PASSWORD = '".$password."';";
          $retList = [];
  $result = mysqli_query($dbLink, $query);

  while ($row = mysqli_fetch_array($result)) {
    $rowMap = [];
    $rowMap["userId"] = $row["USER_ID"];
    $rowMap["familyName"] = $row["FAMILY_NAME"];
    $rowMap["firstName"] = $row["FIRST_NAME"];
    $rowMap["insDateTime"] = $row["INS_DATE_TIME"];
    $rowMap["updDateTime"] = $row["UPD_DATE_TIME"];
    $retList[] = $rowMap;
  }
  // メモリのクリア
  mysqli_free_result($result);
  return $retList;
}


// ユーティリティ ==========

/**
 * サーバーサイドの処理を実行する。
 * @$mainFunc コールバック関数
 */
$chkNo;
function execAction($mainFunc) {
  global $chkNo;
  session_start();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // トークンチェック
    if (isset($_POST["chkNo"]) && isset($_SESSION["chkNo"])) {
      if ($_POST["chkNo"] == $_SESSION["chkNo"]) {
        // DBコネクションの取得
        $dbLink = DBUtil::getDBLink();
        $mainFunc($dbLink);
        // DBコネクションクローズ
        mysqli_close($dbLink);
      }
    }
  }
  $_SESSION["chkNo"] = $chkNo = mt_rand();
}

/**
 * 入力チェックを行うユーティリティクラス
 */
class ValidateUtil {
  /**
   * 必須チェックを行う。
   * @param $value 入力値
   * @return 判定結果 true:空文字・null以外 false:空文字またはnull
   */
  public static function checkNotEmpty($value) {
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
  public static function checkMaxlength($value, $maxLength) {
    $retFlag = false;
    if (mb_strlen($value) <= $maxLength) {
      $retFlag = true;
    }
    return $retFlag;
  }
}

/**
 * DBユーティリティクラス
 */
class DBUtil {
  /**
   * データベースコネクションを取得する。
   * @return データベースコネクション
   */
  public static function getDBLink() {
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
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="/codecamp/webap/css/codecamp.css">
</head>
<body>
  <div id="error-messages">
    <?php foreach($errorMessages as $errorMessage) { ?>
        <p class="error-message"><?php print $errorMessage ?></p>
    <?php } ?>
  </div>
  <form method="POST">
    <input type="hidden" name="chkNo" value="<?php print $chkNo?>">
    ユーザID：<input type="text" name="userId" value="<?php print $userId?>"><br>
    パスワード：<input type="text" name="password" value="<?php print $password?>"><br>
    <input type="submit" name="login" value="ログイン">
  </form>
</body>
</html>