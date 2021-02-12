<?php
$new_name = '';
$new_price = '';
$new_stock = '';
$new_img = '';
$new_status = '';
$host = 'localhost';
$username = 'root';
$passwd = '';
$dbname = 'codecamp';
$drink_data = [];
$err_msg = [];
$succ_msg = '';
$folder = './img_file/';
$link = mysqli_connect($host, $username, $passwd, $dbname);
if ($link) {
    mysqli_set_charset($link, 'utf8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['sql_kind']) === TRUE) {
            $sql_kind = $_POST['sql_kind'];
        }
        $date = date('Y-m-d H:i:s');

        if ($sql_kind === 'insert') {
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
            if (isset($_POST['drink_id']) === TRUE) {
            $drink_id = $_POST['drink_id'];
            }
            if (isset($_POST['new_status']) === TRUE) {
                $new_status = $_POST['new_status'];
            }
            
            if ($new_name === '') {
                $err_msg[] = '商品名を入力してください';
            }
            if ($new_price === '') {
                $err_msg[] = '価格を入力してください';
            } else if (preg_match('/^[0-9]+$/',$new_price) !== 1) {
                $err_msg[] ='価格は0以上の整数値を入力してください';
            } 
            
            if ($new_stock === '') {
                $err_msg[] = '個数を入力してください';
            } else if (preg_match('/^[0-9]+$/',$new_stock) !== 1) {
                $err_msg[] ='個数は0以上の整数値を入力してください';
            }
            if ($new_status !== '1' && $new_status !== '2') {
                $err_msg[] = 'ステータスを選択してください';
            }
        
            if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
                $type = exif_imagetype($_FILES['new_img']['tmp_name']);
                if ($type === false) {
                    $err_msg[] ='画像ファイルを選択してください';
                } else if ($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG) {
                    $err_msg[] ='画像ファイルの形式が違います';
                } else {
                    $new_img = $_FILES['new_img']['name'];
                    if (move_uploaded_file($_FILES['new_img']['tmp_name'],$folder.$new_img) === false) {
                        $err_msg[] ='画像ファイルのアップロード失敗';
                    }
                }
            } else {
                $err_msg[] = '画像ファイルを選択してください';
            }
            //公開ステータスを取得//
            if (count($err_msg) === 0) {
                mysqli_autocommit($link, false);
                //データをまとめる//
                $data = [
                    // 'drink_id' => $drink_id,
                    'drink_name' => $new_name,
                    'price' => $new_price,
                    'created_date' => $date,
                    'update_date' => $date,
                    'status' => $new_status,
                    'pic' => $new_img
                ];
                $query  = 'INSERT INTO drink_table(drink_name, price, created_date, update_date, status, pic)';
                $query .= ' VALUES (\'' . implode('\',\'', $data) . '\')';
                if (mysqli_query($link, $query) === TRUE) {
                    //drink_idのA_Iを取得//
                    $drink_id = mysqli_insert_id($link);
                    // もう一つのINSERT
                    $query  = "INSERT INTO drink_stock_table(drink_id, stock, created_date, update_date)";
                    $query .= " VALUES ('" . $drink_id . "','" . $new_stock . "','" . $date . "','" . $date . "')";
                    if (mysqli_query($link, $query) === false) {
                        $err_msg[] = "SQL実行失敗:" . $query;
                    }
                } else {
                    $err_msg[] = "SQL実行失敗:" . $query;
                }
                if (count($err_msg) === 0) {
                    mysqli_commit($link);
                    $succ_msg = '商品の追加が完了しました';
                } else {
                    mysqli_rollback($link);
                }
            }
        } else if ($sql_kind === 'update') {
            
             if (isset($_POST['update_stock']) === TRUE) {
             $update_stock = $_POST['update_stock'];
             }
             if (isset($_POST['drink_id']) === TRUE) {
             $drink_id = $_POST['drink_id'];
             }   
             if (preg_match('/^[0-9]+$/',$update_stock) !== 1) {
                $err_msg[] = '個数は0以上の整数を入力してください';
             } 
             if (count($err_msg) === 0) {
                $query = "UPDATE drink_stock_table SET stock = '" . $update_stock . "' WHERE drink_id = '" . $drink_id . "'";
                mysqli_query($link, $query);
                if (mysqli_query($link, $query) === TRUE) {
                    $succ_msg = '在庫数の変更が正常に完了しました';
                } 
          }
                
           
            } else if ($sql_kind === 'change') {
                if (isset($_POST['change_status']) === TRUE) {
                    $change_status = $_POST['change_status'];
                }
                if (isset($_POST['drink_id']) === TRUE) {
                    $drink_id = $_POST['drink_id'];
                }      
                 
                $query = "UPDATE drink_table SET status = '". $change_status."' WHERE drink_id = '".$drink_id."'";
                if (mysqli_query($link,$query) === TRUE) {
                    $succ_msg = 'ステータスの変更が正常に完了しました'; 
                }
            }
    }
    
    //一覧の表示//
    $query = 'SELECT drink_table.drink_id, drink_table.pic, drink_table.drink_name, drink_table.price,drink_stock_table.stock, drink_table.status';
    $query .= ' FROM drink_table JOIN drink_stock_table ON drink_table.drink_id = drink_stock_table.drink_id';

    if ($result = mysqli_query($link, $query)) {
        while ($row = mysqli_fetch_array($result)) {
            $drink_data[] = $row;
        }
        mysqli_free_result($result);
        mysqli_close($link);
    } else {
        print 'SELECT失敗';
        print $query;
    }
} else {
    $err_msg[] = "DB接続エラー";
}

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

        table,
        tr,
        th,
        td {
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
<?php if (count($err_msg) !== 0) { 
        foreach($err_msg as $msg) { ?>
        <p><?php print $msg; ?></p>
    <?php } ?>
<?php } ?>
<?php if (empty($succ_msg) !== TRUE) { ?>
    <p><?php print $succ_msg;  ?></p>
<?php } ?>
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
                    <option value="0">公開ステータス</option>
                    <option value="1">非公開</option>
                    <option value="2">公開</option>
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
                <?php foreach ($drink_data as $value) { ?>
                    <tr <?php if ($value['status'] === '1') {?> class="status_false"<?php } ?>>
                    <form method="post">
                        <td><img src="<?php print $folder.htmlspecialchars($value['pic'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <td class="drink_name_width"><?php print htmlspecialchars($value['drink_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text_align_right"><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><input type="text" class="input_text_width text_align_right" name="update_stock" value="<?php print htmlspecialchars($value['stock'], ENT_QUOTES, 'UTF-8'); ?>">個&nbsp;&nbsp;<input type="submit" value="変更"></td>
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                        <input type="hidden" name="sql_kind" value="update">
                    </form>
                    <form method="post">
                        <td>
                            <?php if ($value['status'] === '1') { ?>
                                <input type="submit" value="非公開 → 公開">
                                <input type="hidden" name="change_status" value="2">
                            <?php }else if ($value['status'] === '2') {?>
                                <input type="submit" value="公開 → 非公開">
                                <input type="hidden" name="change_status" value="1">
                            <?php }?>
                            <input type="hidden" name="drink_id" value="<?php print $value['drink_id'];?>">
                            <input type="hidden" name="sql_kind" value="change">
                        </td>           
                    </form>
                    <tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>