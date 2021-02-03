<?php
$host = 'localhost';
$user = 'codecamp38342';
$passwd = 'codecamp38342';
$dbname = 'codecamp38342';
$customer_id = 1;
$message = '';
$point = 0;
$err_msg = [];
$point_gift_list = [];





if ($link = mysqli_connect($host,$user,$passwd,$dbname)) {
    mysqli_set_charset($link,'UTF8');
    
    $sql = 'SELECT point FROM point_customer_table WHERE customer_id = ' . $customer_id; 
    if ($result = mysqli_query($link, $sql)) { 
        $row = mysqli_fetch_assoc($result); 
        if (isset($row['point']) === TRUE) { 
            $point = $row['point'];
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    mysqli_free_result($result);
    
    
   
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//***ここから自分のコード***//
       
        //購入時間を取得//
        $date = date('Y-m-d H:i:s');
        
        //ポイントのIDを取得//
        $point_gift_id = (int) $_POST['point_gift_id'];
       
        //トランザクション開始//
        mysqli_autocommit($link, false);
        $sql = 'SELECT point,name FROM point_gift_table WHERE point_gift_id =' .$point_gift_id;
        if ($result = mysqli_query($link,$sql)) {
            $row = mysqli_fetch_assoc($result);
            if (isset($row['point']) === TRUE) {
                $gift_point = $row['point'];
            }
            if (isset($row['name']) === TRUE) {
                $gift_name = $row['name'];
            } 
        }
        //情報をまとめる//
        $data = [
            'customer_id' => $customer_id,
            'point_gift_id' => $point_gift_id,
            'created_at' => $date
            ];
        
        //insert文//
        $sql= 'INSERT INTO point_history_table(customer_id, point_gift_id, created_at) VALUES(\'' .implode('\',\'',$data) . '\')';
        
        //insert実行//
        if (mysqli_query($link,$sql) === TRUE) {
            
            // point_customer_tableの顧客保有ポイントをUPDTE
           // $sql = 'UPDATE point_customer_table SET point =' .$point - $gift_point. 'WHERE customer_id = \''.$customer_id.'\'';//
            $sql = 'UPDATE point_customer_table SET point = 50 WHERE customer_id = 1';
            if (mysqli_query($link,$sql) !== TRUE) {
                 $err_msg[] = 'SQL失敗:' . $sql;
            }
        } else {
            $err_msg[] = 'SQL失敗:' . $sql;
            
        }
        if (count($err_msg) === 0) {
            mysqli_commit($link);
            $message = $gift_name.'を購入しました';
        } else {
            mysqli_rollback($link);
            
        }
        
    }else {
        
    }
    
    
    $sql = 'SELECT point_gift_id, name, point FROM point_gift_table';
    if ($result = mysqli_query($link, $sql)) {
    print 'misa';
        $i = 0;   
        while($row = mysqli_fetch_assoc($result)) {
            $point_gift_list[$i]['point_gift_id'] = htmlspecialchars($row['point_gift_id'], ENT_QUOTES, 'UTF-8');
            $point_gift_list[$i]['name']       = htmlspecialchars($row['name'],       ENT_QUOTES, 'UTF-8');
            $point_gift_list[$i]['point']      = htmlspecialchars($row['point'],      ENT_QUOTES, 'UTF-8');
            $i++;
         }
    } else {
        $err_msg[] =  'SQL失敗:' . $sql;
    }
    mysqli_free_result($result);
    mysqli_close($link);
} else {
    $err_msg[] = 'error' . mysqli_connect_error();
}


?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <title>トランザクション課題</title>
</head>
<body>
<?php if (empty($message) !== TRUE) { ?>
   <p><?php print $message; ?></p>
<?php } ?>
   <section>
       <h1>保有ポイント</h1>
       <p><?php print number_format($point); ?>ポイント</p>
   </section>
   <section>
       <h1>ポイント商品購入</h1>
        <form method="post">
               <ul>
    <?php       foreach ($point_gift_list as $point_gift) { ?>
                   <li>
                       <span><?php print $point_gift['name']; ?></span>
                       <span><?php print number_format($point_gift['point']); ?>ポイント</span>
    <?php           if ($point_gift['point'] <= $point) { ?>
                       <button type="submit" name="point_gift_id" value="<?php print $point_gift['point_gift_id']; ?>">購入する</button>
    <?php        }else{ ?>
                       <button type="button" disabled="disabled">購入不可</button>
    <?php        } ?>
                   </li>
    <?php    } ?>
               </ul>
           </form>
           <p>※サンプルのためポイント購入は1景品 & 1個に固定</p>
       </section>
    </body>
    </html>