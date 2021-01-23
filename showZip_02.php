<?php
// http://localhost:80/codecamp/showZip_02.php
// SplFileObjectを使用した例
$fileName = './zip_data_split_1.csv';
$data = [];
showZipCsvFile($fileName);

/**
 * CSVファイルの内容を画面に表示する。
 * @param $fileName ファイル名
 */
function showZipCsvFile($fileName) {
    global $data;
    if (is_readable($fileName)) {
        global $data;
        $zipCsvFile = new SplFileObject($fileName, "r");
        $zipCsvFile -> setFlags(SplFileObject::READ_CSV);
        $header = ["zip","prefKana","muniKana","areaKana","pref","muni","area"];
        foreach ($zipCsvFile as $row) {
            if ($row === [null]) continue; // 最終行の処理
            $data[] = array_combine($header, $row);
        }
    } else {
        print "ファイルの読み込みが出来ません";
    }
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
            <td><?php print htmlspecialchars($rowMap["zip"])?></td>
            <!-- 都道府県 -->
            <td><?php print htmlspecialchars($rowMap["pref"])?></td>
            <!-- 市区町村 -->
            <td><?php print htmlspecialchars($rowMap["muni"])?></td>
            <!-- 町域 -->
            <td><?php print htmlspecialchars($rowMap["area"])?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>