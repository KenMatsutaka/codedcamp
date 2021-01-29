<?php
/**
 * 課題番号：17-6
 * 住所検索をDBを使用して作成する。
 * 接続先URL：http://localhost:80/codecamp/17_6_addressDB.php
 */
// 画面入力情報 ----------
// 郵便番号
$zipcode = "";
// 都道府県
$pref = "";
// 市区町村
$localGov = "";
// 現在のページ
$pageNum = 1;

// 画面出力情報 ----------
// 住所ページング情報
$addressPagingInfo = array("hitCount" => 0, "resultList" => array(), "existPrevPage" => false,  "existNextPage" => false);

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // メイン処理 開始 ------------
  // DBコネクション取得
  $link = getDBLink();
  if (isset($_POST["searchMethod"])) {
    $searchMethod = $_POST["searchMethod"];
    // 郵便番号検索
    if ($searchMethod === "zipcode") {
      if (isset($_POST["zipcode"])) {
        $zipcode = $_POST["zipcode"];
      }
      if(isset($_POST["pageNum"])) {
        $pageNum = intval($_POST["pageNum"]);
      }
      // TODO 入力チェック
      // 検索処理実行
      $resultList = findAddress($link, $zipcode, null, null);
      $addressPagingInfo = calcPagingInfo($resultList, $pageNum);
    // 地名から検索
    } else {
      // 都道府県
      if (isset($_POST["pref"])) {
        $pref = $_POST["pref"];
      }
      if(isset($_POST["pageNum"])) {
        $pageNum = intval($_POST["pageNum"]);
      }
      // 市区町村
      if (isset($_POST["localGov"])) {
        $localGov = $_POST["localGov"];
      }
      // TODO 入力チェック
      // 検索処理実行
      $resultList = findAddress($link, null, $pref, $localGov);
      $addressPagingInfo = calcPagingInfo($resultList, $pageNum);
    }
  }
  // DBコネクションクローズ
  mysqli_close($link);
  // メイン処理 終了 ------------
}

/**
 * ページング情報を計算する
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
      print $pageNum;
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
      </style>
  </head>
  <body>
    <h1>郵便番号検索</h1>
    <section>
      <h2>郵便番号から検索</h2>
      <form method="POST" id="searchForm">
        <input type="text" name="zipcode" placeholder="例）1010001" value="0600000">
        <input type="hidden" id="searchMethod" name="searchMethod" value="">
        <input type="hidden" id="pageNum" name="pageNum" value="<?php print $pageNum?>">
        <input type="submit" id="zipcodeSearch" value="検索">
        <h2>地名から検索</h2>
        都道府県を選択
        <select name="pref">
          <option value="" selected>都道府県を選択</option>
          <option value="北海道"  selected>北海道</option>
          <option value="青森県" >青森県</option>
          <option value="岩手県" >岩手県</option>
          <option value="宮城県" >宮城県</option>
          <option value="秋田県" >秋田県</option>
          <option value="山形県" >山形県</option>
          <option value="福島県" >福島県</option>
          <option value="茨城県" >茨城県</option>
          <option value="栃木県" >栃木県</option>
          <option value="群馬県" >群馬県</option>
          <option value="埼玉県" >埼玉県</option>
          <option value="千葉県">千葉県</option>
          <option value="東京都" >東京都</option>
          <option value="神奈川県" >神奈川県</option>
          <option value="新潟県" >新潟県</option>
          <option value="富山県" >富山県</option>
          <option value="石川県" >石川県</option>
          <option value="福井県" >福井県</option>
          <option value="山梨県" >山梨県</option>
          <option value="長野県" >長野県</option>
          <option value="岐阜県" >岐阜県</option>
          <option value="静岡県" >静岡県</option>
          <option value="愛知県" >愛知県</option>
          <option value="三重県" >三重県</option>
          <option value="滋賀県" >滋賀県</option>
          <option value="京都府" >京都府</option>
          <option value="大阪府" >大阪府</option>
          <option value="兵庫県" >兵庫県</option>
          <option value="奈良県" >奈良県</option>
          <option value="和歌山県" >和歌山県</option>
          <option value="鳥取県" >鳥取県</option>
          <option value="島根県" >島根県</option>
          <option value="岡山県" >岡山県</option>
          <option value="広島県" >広島県</option>
          <option value="山口県" >山口県</option>
          <option value="徳島県" >徳島県</option>
          <option value="香川県" >香川県</option>
          <option value="愛媛県" >愛媛県</option>
          <option value="高知県" >高知県</option>
          <option value="福岡県" >福岡県</option>
          <option value="佐賀県" >佐賀県</option>
          <option value="長崎県" >長崎県</option>
          <option value="熊本県" >熊本県</option>
          <option value="大分県" >大分県</option>
          <option value="宮崎県" >宮崎県</option>
          <option value="鹿児島県" >鹿児島県</option>
          <option value="沖縄県" >沖縄県</option>
        </select>
        市区町村
        <input type="text" name="localGov" value="札幌市中央">
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
        <td><?php print htmlspecialchars($rowMap["addressId"].":".$rowMap["zip"]);?></td>
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