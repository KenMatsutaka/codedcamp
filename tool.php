<?php
$new_name = '';
$new_price = '';
$new_stock = '';
$new_img = '';
$new_status = '';
$host = 'localhost';
$username = 'codecamp38342';
$passwd = 'codecamp38342';
$dbname = 'codecamp38342';
$drink_data = [];
$link = mysqli_connect($host, $username,$passwd, $dbname);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //作成日を取得//
    $date = date('Y-m-d H:i:s');
    //名前・個数・値段の値を取得//
    if (isset($_POST['new_name']) === TRUE) {
        $new_name = $_POST['new_name'];
    }
    if (isset($_POST['new_price']) === TRUE) {
        $new_price = $_POST['new_price'];
    }
    if (isset($_POST['new_stock']) === TRUE) {
        $new_stock = $_POST['new_stock'];
    }
    //商品画像を取得
    // FIXME ファイルの情報は後
    $new_img = '';
    // if (isset($_POST['new_img']) === TRUE) {
    //     $new_img = $_POST['new_img'];
    // }
    //公開ステータスを取得//
    if (isset($_POST['new_status']) === TRUE) {
        $new_status = $_POST['new_status'];
    }
    if ($link) {
        mysqli_set_charset($link,'utf8');
        //データをまとめる//
        $data = [
            // 'drink_id' => $drink_id,
            'drink_name' => $new_name,
            'price' => $new_price,
            'created_date' => $date,
            'status' => $new_status,
            'pic' => $new_img
            ];
        $query  = 'INSERT INTO drink_table(drink_name, price, created_date, status, pic)';
        $query .= 'VALUES (\'' .implode('\',\'',$data). '\')';
        if (mysqli_query($link, $query) === TRUE) {
            //drink_idのA_Iを取得//
            $drink_id = mysqli_insert_id($link);
            // もう一つのINSERT
            $query  = '';
        } else {
            print "SQL実行失敗:".$query;
        }
    } else {
        print "DB接続エラー";
    }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // //作成日を取得//
    // $date = date('Y-m-d H:i:s');
    
    // //名前・個数・値段の値を取得//
    // if (isset($_POST['new_name']) === TRUE) {
    //     $new_name = $_POST['new_name'];
    // }
    // if (isset($_POST['new_price']) === TRUE) {
    //     $new_price = $_POST['new_price'];
    // }
    // if (isset($_POST['new_stock']) === TRUE) {
    //     $new_stock = $_POST['new_stock'];
    // }
    
    // //商品画像を取得//
    // if (isset($_POST['new_img']) === TRUE) {
    //     $new_img = $_POST['new_img'];
    // }
    
    // //公開ステータスを取得//
    // if (isset($_POST['new_status']) === TRUE) {
    //     $new_status = $_POST['new_status'];
    // }
    //drink_idのA_Iを取得//
    // $drink_id = mysqli_insert_id($link);
    
    //insertする//
    // if ($link) {
        // mysqli_set_charset($link,'utf8');
        // //データをまとめる//
        // $data = [
        //     'drink_id' => $drink_id,
        //     'drink_name' => $new_name,
        //     'price' => $new_price,
        //     'stock' => $new_stock,
        //     'created_date' => $date,
        //     'status' => $new_status,
        //     'pic' => $new_img
        //     ];
        // $query = 'INSERT INTO drink_table(drink_id, drink_name, price, created_date, status, pic)
        //           VALUES (\'' .implode('\',\'',$data). '\')';
    　　　//$query = 'INSERT INTO drink_table(drink_id, drink_name, price, created_date, status, pic)';
        //   if (mysqli_query($link, $query) === TRUE) {
        //     print 'insert成功';
        // }   
       
        // $query = 'SELECT pic.drink_table, drink_name.drink_table, price.drink_table, stock.drink_stock_table, status.drink_table';
        // $query .= 'FROM dink_table JOIN drink_stock_table';
        // $query .= 'ON drink_table.drink_id = drink_stock_table.drink_id';
        
        // if ($result = mysqli_query($link,$query)) {
        //     while ($row = mysqli_fetch_array($result)) {
        //         $drink_data[] = $row;
        //     }
        //      mysqli_free_result($result);
        //      mysqli_close($link);
        // }else {
        //     print 'SELECT失敗';
        // } 
        
    // } else {
    //    print '接続失敗';
    // }
// }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自動販売機</title>
    <style>
        section {
            margin-bottom: 20px;
            border-top: solid 1px;
        }

        table {
            width: 660px;
            border-collapse: collapse;
        }

        table, tr, th, td {
            border: solid 1px;
            padding: 10px;
            text-align: center;
        }

        caption {
            text-align: left;
        }

        .text_align_right {
            text-align: right;
        }

        .drink_name_width {
            width: 100px;
        }

        .input_text_width {
            width: 60px;
        }

        .status_false {
            background-color: #A9A9A9;
        }
    </style>
</head>
<body>
    <h1>自動販売機管理ツール</h1>
    <section>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <div><label>名前: <input type="text" name="new_name" value=""></label></div>
            <div><label>値段: <input type="text" name="new_price" value=""></label></div>
            <div><label>個数: <input type="text" name="new_stock" value=""></label></div>
            <div><input type="file" name="new_img"></div>
            <div>
                <select name="new_status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="■□■□■商品追加■□■□■"></div>
        </form>
    </section>
    <section>
        <h2>商品情報変更</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
            </tr>
            
            <tr class="status_false">
<?php foreach($drink_data as $value) { ?>                
                <form method="post">
                    <td><img src="<?php print htmlspecialchars($value['pic'],ENT_QUOTES,'UTF-8'); ?>"></td>
                    <td class="drink_name_width"><?php print htmlspecialchars($value['drink_name'],ENT_QUOTES,'UTF-8'); ?></td>
                    <td class="text_align_right"><?php print htmlspecialchars($value['price'],ENT_QUOTES,'UTF-8'); ?></td>
                    <td><input type="text"  class="input_text_width text_align_right" name="update_stock" value="<?php print htmlspecialchars($value['stock'],ENT_QUOTES,'UTF-8'); ?>">個&nbsp;&nbsp;<input type="submit" value="変更"></td>
                    <input type="hidden" name="drink_id" value="">
                    <input type="hidden" name="sql_kind" value="update">
                </form>
                <form method="post">
                    <td><input type="submit" value="非公開 → 公開"></td>
                    <td><input type="submit" value="非公開 → 公開"></td>
                    <input type="hidden" name="change_status" value="1">
                    <input type="hidden" name="drink_id" value="">
                    <input type="hidden" name="sql_kind" value="change">
                </form>
<?php } ?>             
              <tr>
        </table>
    </section>
</body>
</html>