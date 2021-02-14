<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>商品一覧画面</title>
    <script>
        $(function (){});
    </script>
    <style>
        .flex {
            width: 600px;
        }

        .flex .item {
            //border: solid 1px;
            width: 120px;
            height: 210px;
            text-align: center;
            margin: 10px;
            float: left;
        }

        .flex span {
            display: block;
            margin: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .img_size {
            height: 125px;
        }
        .red {
            color: #FF0000;
        }
        #submit {
            clear: both;
        }
    </style>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <h1>商品一覧画面</h1>
    <div class="flex">
        <?php foreach($open_item_list as $open_item) { ?>
            <div class="item">
                <span class="img_size"><img src="./img/1/test.jpg"></span>
                <span><?php print htmlspecialchars($open_item["name"], ENT_QUOTES, 'UTF-8');?></span>
                <span><?php print htmlspecialchars($open_item["price"], ENT_QUOTES, 'UTF-8');?>円</span>
                <span class="red">売り切れ</span>
            </div>
        <?php }?>
    </div>
</body>
</html>