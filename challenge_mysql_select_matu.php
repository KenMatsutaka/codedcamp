<?php
// http://localhost:80/codecamp/challenge_mysql_select_matu.php
$emp_data = [];
$job = "";
// TODO POSTから①セレクトボックスの値を取得する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['job'])) {
        $job = $_POST['job'];
    }
    $host = 'localhost';
    $username = 'root';
    $passwd = '';
    $dbname = 'codecampdb';
    $link = mysqli_connect($host, $username, $passwd, $dbname);
    if ($link) {
        mysqli_set_charset($link, 'utf8');
        $query = "SELECT emp_id, emp_name, job, age FROM emp_table WHERE job= '".$job."' ORDER BY emp_id ";
        $result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($result)) {
            $emp_data[] = $row;
        }
        mysqli_free_result($result);
        mysqli_close($link);
    } else {
        print 'DB接続失敗';
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>challenge_mysql_select.php</title>
    <style type="text/css">
        table, td, th {
            border: solid black 1px;
        }
        table {
            width: 300px;
        }
    </style>
</head>
<body>
    <h1>表示する職種を選択してください。</h1>
    <form method="POST">
        <select name="job">
            <option value="">全員</option>
            <option value="manager">マネージャー</option>
            <option value="analyst">アナリスト</option>
            <option value="clerk">一般職</option>
        </select>
        <input type="submit" value="表示">
    </form>
    <table>
        <tr>
            <th>社員番号</th>
            <th>名前</th>
            <th>職種</th>
            <th>年齢</th>
        </tr>
        
<?php
foreach ($emp_data as $value) {
?>    
        <tr>
            <td><?php print htmlspecialchars($value['emp_id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['emp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['job'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['age'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
<?php
}
?>
    </table>
</body>
</html>