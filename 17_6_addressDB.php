<?php
/**
 * 課題番号：17-6
 * 住所検索をDBを使用して作成する。
 * 接続先URL：http://localhost:80/codecamp/17_6_addressDB.php
 */
// 画面入力情報 ==========
// 郵便番号
$zipcode = "";
// 都道府県
$pref = "";
// 市区町村
$localGov = "";
// 現在のページ
$pageNum = 1;

// 画面出力情報 ==========
// 住所ページング情報
$addressPagingInfo = array("hitCount" => 0, "resultList" => array(), "existPrevPage" => false,  "existNextPage" => false);
// エラーメッセージ情報
$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // メイン処理 開始 ==========
  // DBコネクション取得
  $link = getDBLink();
  if (isset($_POST["searchMethod"])) {
    // 入力項目の取得 ----------
    // 検索メソッド
    $searchMethod = $_POST["searchMethod"];
    //　郵便番号
    if (isset($_POST["zipcode"])) {
      $zipcode = $_POST["zipcode"];
    }
    // 都道府県
    if (isset($_POST["pref"])) {
      $pref = $_POST["pref"];
    }
    // 市区町村
    if (isset($_POST["localGov"])) {
      $localGov = $_POST["localGov"];
    }
    // 現在ページ
    if(isset($_POST["pageNum"])) {
      $pageNum = intval($_POST["pageNum"]);
    }
    // 入力チェック ----------
    if (checkInputValue()) {    // 検索処理 ----------
      if ($searchMethod === "zipcode") {
        $resultList = findAddress($link, $zipcode, null, null);
      } else {
        $resultList = findAddress($link, null, $pref, $localGov);
      }
      // ページング情報の計算 ----------
      $addressPagingInfo = calcPagingInfo($resultList, $pageNum);
    }
  }
  // DBコネクションクローズ
  mysqli_close($link);
  // メイン処理 終了 ==========
}

/**
 * 入力チェック処理を行う。
 * @return チェック結果 true:入力エラーなし false:入力チェックエラーあり
 */
function checkInputValue() {
  global $searchMethod, $zipcode, $pref, $localGov;
  global $errorMessages;
  if ($searchMethod === "zipcode") {
    // 郵便番号 ----------
    // 必須チェック
    if (!checkNotEmpty($zipcode)) {
      $errorMessages[] = "郵便番号は必須です。";
    }
    // 郵便番号チェック
    if (!checkZipcode($zipcode)) {
      $errorMessages[] = "郵便番号は7桁の数字で入力してください。";
    }
  } else {
    // 都道府県 ----------
    // 必須チェック
    if (!checkNotEmpty($pref)) {
      $errorMessages[] = "都道府県は必須です。";
    }
    // 市区町村 ----------
    // 必須チェック
    if (!checkNotEmpty($localGov)) {
      $errorMessages[] = "市区町村は必須です。";
    }
  }
  
  // チェック結果の判定
  $retFlag = false;
  if (count($errorMessages) === 0) {
    $retFlag = true;
  }
  return $retFlag;
}
/**
 * ページング情報を計算する
 * @param $originalList 検索結果
 * @param $pageNum 表示ページ数
 */
function calcPagingInfo($originalList, $pageNum) {
  $retMap;
  // 検索件数
  $hitCount = count($originalList);
  $retMap["hitCount"] = $hitCount;
  //表示件数
  $dispCount = 10;
  $retMap["dispCount"] = $dispCount;
  //総ページ数
  $totalPageCount = floor($hitCount / $dispCount);
  if ($hitCount % $dispCount > 0) {
    $totalPageCount++;
  }
  $retMap["totalPageCount"] = $totalPageCount;
  // 開始インデックス
  $startIndex = ($pageNum - 1) * $dispCount;
  $retMap["startIndex"] = $startIndex;
  // 終了インデックス
  $endIndex = $startIndex + $dispCount;
  if($hitCount < $endIndex) {
    $endIndex = $hitCount;
  }
  // 検索結果
  $resultList = [];
  for ($index = $startIndex; $index < $endIndex; $index++) {
    $resultList[] = $originalList[$index];
  }
  $retMap["resultList"] = $resultList;
  // 前のページ・次ページ存在チェック
  $existPrevPage = false;
  $existNextPage = false;
  if ($hitCount !== 0) {
    if($pageNum !== 1) {
      $existPrevPage = true;
    }
    if ($pageNum < $totalPageCount) {
      $existNextPage = true;
    }
  }
  $retMap["existPrevPage"] = $existPrevPage;
  $retMap["existNextPage"] = $existNextPage;
  return $retMap;
}

/**
 * 住所情報を取得する
 * @param $link DBコネクション
 * @param $zipcode 郵便番号
 * @param $pref 都道府県
 * @param $localGov 市区町村
 * @return 住所情報
 */
function findAddress($link, $zipcode, $pref, $localGov) {
  $retList = [];
  // 実行SQL作成 ----------
  $query = "SELECT ADDRESS_ID, ZIP, PREF_KANA, LOCAL_GOV_KANA, AREA_KANA, PREF, LOCAL_GOV, AREA FROM ADDRESS_MST ";
  // 検索条件の生成
  $searchConditions = [];
  // 郵便番号
  if ($zipcode !== null && $zipcode !== "") {
    $searchConditions[] = "ZIP = '".$zipcode."' ";
  }
  // 都道府県
  if ($pref !== null && $pref !== "") {
    $searchConditions[] = "PREF = '".$pref."' ";
  }
  // 市区町村
  if ($localGov !== null && $localGov !== "") {
    $searchConditions[] = "LOCAL_GOV LIKE '%".$localGov."%' ";
  }

  $where = "";
  $firstFlg = true;
  foreach($searchConditions as $searchCondition) {
    if($firstFlg) {
      $firstFlg = false;
      $where .= "WHERE ";
    } else {
      $where .= "AND ";
    }
    $where .= $searchCondition;
  }
  $query .= $where;
  // ソート条件設定
  $query .= "ORDER BY ADDRESS_ID ASC ";
  
  // SQL実行
  $result = mysqli_query($link, $query);
  // 検索結果の設定
  while ($row = mysqli_fetch_array($result)) {
    $rowMap = [];
    // 住所ID
    $rowMap["addressId"] = $row["ADDRESS_ID"];
    // 郵便番号
    $rowMap["zip"] = $row["ZIP"];
    // 都道府県ｶﾅ
    $rowMap["prefKana"] = $row["PREF_KANA"];
    // 市区町村ｶﾅ
    $rowMap["localGovKana"] = $row["LOCAL_GOV_KANA"];
    // 町域ｶﾅ
    $rowMap["areaKana"] = $row["AREA_KANA"];
    // 都道府県
    $rowMap["pref"] = $row["PREF"];
    // 市区町村
    $rowMap["localGov"] = $row["LOCAL_GOV"];
    // 町域ｶﾅ
    $rowMap["area"] = $row["AREA"];
    $retList[] = $rowMap;
    }
  // メモリのクリア
  mysqli_free_result($result);
  return $retList;
}

// ユーティリティ ----------
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
 * 郵便番号として妥当かチェックを行う
 * @param $value 入力値
 */
function checkZipcode($value) {
  $retFlg = true;
  if (checkNotEmpty($value)) {
    if (!preg_match("/[0-9]{7}/", $value)) {
      $retFlg = false;
    }
  }
  return $retFlg;
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
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>郵便番号検索</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
      $(function (){

        /**
         * 郵便番号-検索ボタン押下時の処理
         */
        $("#zipcodeSearch").click(function () {
          // 検索種類の設定
          $("#searchMethod").val("zipcode");
          // 表示ページの設定
          $("#pageNum").val("1");
          // サブミット
          $("#searchForm").submit();
        });

        /**
         * 住所-検索ボタン押下時の処理
         */
        $("#addressSearch").click(function () {
          // 検索種類の設定
          $("#searchMethod").val("address");
          // 表示ページの設定
          $("#pageNum").val("1");
          // サブミット
          $("#searchForm").submit();
        });

        /** 
        * [前のページ]リンククリック時の処理
        */
        $("#prevPageLink").click(function () {
          // 検索種類の設定
          $("#searchMethod").val("address");
          // 表示ページの設定
          var prevPageNum = Number($("#pageNum").val()) - 1;
          $("#pageNum").val(prevPageNum);
          $("#searchForm").submit();
        });

        /**
        * [次のページ]リンククリック時の処理
        */
        $("#nextPageLink").click(function () {
          // 検索種類の設定
          $("#searchMethod").val("address");
          // 表示ページの設定
          var nextPageNum = Number($("#pageNum").val()) + 1;
          $("#pageNum").val(nextPageNum);
          $("#searchForm").submit();
        });
      });
    </script>
    <style>
          .search_reslut {
              border-top: solid 1px;
              margin-top: 10px;
          }
          table {
              border-collapse: collapse;
          }
          table, tr, th, td {
              border: solid 1px;
          }
          caption {
              text-align: left;
          }
          .errorMessage {
            color: #FF0000;
          }
      </style>
  </head>
  <body>
    <?php foreach($errorMessages as $errorMessage) { ?>
      <p class="errorMessage"><?php print $errorMessage ?></p>
    <?php } ?>
    <h1>郵便番号検索</h1>
    <section>
      <h2>郵便番号から検索</h2>
      <form method="POST" id="searchForm">
        <input type="text" name="zipcode" placeholder="例）1010001" value="<?php print $zipcode;?>">
        <input type="hidden" id="searchMethod" name="searchMethod" value="">
        <input type="hidden" id="pageNum" name="pageNum" value="<?php print $pageNum?>">
        <input type="submit" id="zipcodeSearch" value="検索">
        <h2>地名から検索</h2>
        都道府県を選択
        <select name="pref">
          <option value="">都道府県を選択</option>
          <option value="北海道" <?php if ($pref === "北海道") {?>selected<?php }?>>北海道</option>
          <option value="青森県" <?php if ($pref === "青森県") {?>selected<?php }?>>青森県</option>
          <option value="岩手県" <?php if ($pref === "岩手県") {?>selected<?php }?>>岩手県</option>
          <option value="宮城県" <?php if ($pref === "宮城県") {?>selected<?php }?>>宮城県</option>
          <option value="秋田県" <?php if ($pref === "秋田県") {?>selected<?php }?>>秋田県</option>
          <option value="山形県" <?php if ($pref === "山形県") {?>selected<?php }?>>山形県</option>
          <option value="福島県" <?php if ($pref === "福島県") {?>selected<?php }?>>福島県</option>
          <option value="茨城県" <?php if ($pref === "茨城県") {?>selected<?php }?>>茨城県</option>
          <option value="栃木県" <?php if ($pref === "栃木県") {?>selected<?php }?>>栃木県</option>
          <option value="群馬県" <?php if ($pref === "群馬県") {?>selected<?php }?>>群馬県</option>
          <option value="埼玉県" <?php if ($pref === "埼玉県") {?>selected<?php }?>>埼玉県</option>
          <option value="千葉県" <?php if ($pref === "千葉県") {?>selected<?php }?>>千葉県</option>
          <option value="東京都" <?php if ($pref === "東京都") {?>selected<?php }?>>東京都</option>
          <option value="神奈川県" <?php if ($pref === "神奈川県") {?>selected<?php }?>>神奈川県</option>
          <option value="新潟県" <?php if ($pref === "新潟県") {?>selected<?php }?>>新潟県</option>
          <option value="富山県" <?php if ($pref === "富山県") {?>selected<?php }?>>富山県</option>
          <option value="石川県" <?php if ($pref === "石川県") {?>selected<?php }?>>石川県</option>
          <option value="福井県" <?php if ($pref === "福井県") {?>selected<?php }?>>福井県</option>
          <option value="山梨県" <?php if ($pref === "山梨県") {?>selected<?php }?>>山梨県</option>
          <option value="長野県" <?php if ($pref === "長野県") {?>selected<?php }?>>長野県</option>
          <option value="岐阜県" <?php if ($pref === "岐阜県") {?>selected<?php }?>>岐阜県</option>
          <option value="静岡県" <?php if ($pref === "静岡県") {?>selected<?php }?>>静岡県</option>
          <option value="愛知県" <?php if ($pref === "愛知県") {?>selected<?php }?>>愛知県</option>
          <option value="三重県" <?php if ($pref === "三重県") {?>selected<?php }?>>三重県</option>
          <option value="滋賀県" <?php if ($pref === "滋賀県") {?>selected<?php }?>>滋賀県</option>
          <option value="京都府" <?php if ($pref === "京都府") {?>selected<?php }?>>京都府</option>
          <option value="大阪府" <?php if ($pref === "大阪府") {?>selected<?php }?>>大阪府</option>
          <option value="兵庫県" <?php if ($pref === "兵庫県") {?>selected<?php }?>>兵庫県</option>
          <option value="奈良県" <?php if ($pref === "奈良県") {?>selected<?php }?>>奈良県</option>
          <option value="和歌山県" <?php if ($pref === "和歌山県") {?>selected<?php }?>>和歌山県</option>
          <option value="鳥取県" <?php if ($pref === "鳥取県") {?>selected<?php }?>>鳥取県</option>
          <option value="島根県" <?php if ($pref === "島根県") {?>selected<?php }?>>島根県</option>
          <option value="岡山県" <?php if ($pref === "岡山県") {?>selected<?php }?>>岡山県</option>
          <option value="広島県" <?php if ($pref === "広島県") {?>selected<?php }?>>広島県</option>
          <option value="山口県" <?php if ($pref === "山口県") {?>selected<?php }?>>山口県</option>
          <option value="徳島県" <?php if ($pref === "徳島県") {?>selected<?php }?>>徳島県</option>
          <option value="香川県" <?php if ($pref === "香川県") {?>selected<?php }?>>香川県</option>
          <option value="愛媛県" <?php if ($pref === "愛媛県") {?>selected<?php }?>>愛媛県</option>
          <option value="高知県" <?php if ($pref === "高知県") {?>selected<?php }?>>高知県</option>
          <option value="福岡県" <?php if ($pref === "福岡県") {?>selected<?php }?>>福岡県</option>
          <option value="佐賀県" <?php if ($pref === "佐賀県") {?>selected<?php }?>>佐賀県</option>
          <option value="長崎県" <?php if ($pref === "長崎県") {?>selected<?php }?>>長崎県</option>
          <option value="熊本県" <?php if ($pref === "熊本県") {?>selected<?php }?>>熊本県</option>
          <option value="大分県" <?php if ($pref === "大分県") {?>selected<?php }?>>大分県</option>
          <option value="宮崎県" <?php if ($pref === "宮崎県") {?>selected<?php }?>>宮崎県</option>
          <option value="鹿児島県" <?php if ($pref === "鹿児島県") {?>selected<?php }?>>鹿児島県</option>
          <option value="沖縄県" <?php if ($pref === "沖縄県") {?>selected<?php }?>>沖縄県</option>
        </select>
        市区町村
        <input type="text" name="localGov" value="<?php print $localGov;?>">
        <input type="submit" id="addressSearch" value="検索">
      </form>
    </section>
    <section class="search_reslut">
    <p>検索結果<?php print $addressPagingInfo["hitCount"]; ?>件</p>
    <table>
      <caption>郵便番号検索結果</caption>
      <tr>
        <th>郵便番号</th>
        <th>都道府県</th>
        <th>市区町村</th>
        <th>町域</th>
      </tr>
      <?php foreach($addressPagingInfo["resultList"] as $rowMap) {?>
      <tr>
        <td><?php print htmlspecialchars($rowMap["zip"]);?></td>
        <td><?php print htmlspecialchars($rowMap["pref"]);?></td>
        <td><?php print htmlspecialchars($rowMap["localGov"]);?></td>
        <td><?php print htmlspecialchars($rowMap["area"]);?></td>
      </tr>
      <?php } ?>
    </table>
    <?php if($addressPagingInfo["existPrevPage"]) { ?>
    <a href="#" id="prevPageLink">前のページ</a>
    <?php } ?>
    <?php if($addressPagingInfo["existNextPage"]) {?>
    <a href="#" id="nextPageLink">次のページ</a>
    <?php } ?>
    </section>
  </body>
</html>