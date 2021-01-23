<?php
// http://localhost:80/codecamp/showZip_01.php
$fileName = './zip_data_split_1.csv';
$data = [];

// CSVファイルの読み込み
if (is_readable($fileName)) {
    $fp = fopen($fileName, "r");
    if ($fp != false) {
        while (($rowData = fgets($fp)) !== false) {
            $tmpData = explode(",", $rowData);
            $data[] = array(
                // 郵便番号
                "zip" => convertData($tmpData[0]),
                // 都道府県
                "pref" => convertData($tmpData[4]),
                // 市区町村
                "muni" => convertData($tmpData[5]),
                // 町域
                "area" => convertData($tmpData[6])
            );
        }
        fclose($fp);
    } else {
        print "ファイルオープンに失敗しました";
    }
} else {
    print "ファイルの読み込みが出来ません";
}

/**
 * CSVデータを画面表示用に変換
 * ・前後のスペース削除(trim関数)
 * ・前後の「"」削除(mb_substr関数)
 * 　※substr関数はバイト数で処理している為、2バイト文字(日本語)を使用する場合に注意が必要
 * ・html表示用の文字を変換(htmlsplecialcahrs関数)
 * 　メモ:データ格納時ではなく表示するタイミングで変換しても良いかも
 * 引数:変換対象データ
 * 戻り値：変更後のデータ
 */
function convertData($data) {
    $retValue = "";
    if (mb_strlen(trim($data)) > 2) {
        $retValue = htmlspecialchars(mb_substr(trim($data), 1, mb_strlen(trim($data)) - 2));
    }
    return $retValue;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>郵便番号CSVファイル読み込み</title>
    <style>
        #zip-table{
            border-collapse:collapse;
        }
        #zip-table caption {
            text-align: left;
        }
        #zip-table th,#zip-table td {
            border: solid 1px #000000;
        }
    </style>
</head>
<body>
    <table id="zip-table">
        <caption>住所データ</caption>
        <th>郵便番号</th>
        <th>都道府県</th>
        <th>市区町村</th>
        <th>町域</th>
        <?php foreach($data as $rowMap) { ?>
        <tr>
            <!-- 郵便番号 -->
            <td><?php print $rowMap["zip"]?></td>
            <!-- 都道府県 -->
            <td><?php print $rowMap["pref"]?></td>
            <!-- 市区町村 -->
            <td><?php print $rowMap["muni"]?></td>
            <!-- 町域 -->
            <td><?php print $rowMap["area"]?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>