<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>カート画面</title>
    <style>
        .flex {
            width: 600px;
        }

        .flex .item {
            //border: solid 1px;
            width: 120px;
            height: 240px;
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
        .clear {
            clear: both;
        }
    </style>
    <script>
        $(function (){
        });
    </script>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <h1>カート画面</h1>
    <a href="./item_list_controller.php">商品一覧へ</a>
    <?php require_once "./view/common/message.php";?>
    <div class="flex">
        <?php foreach($cart_item_list as $cart_item) { ?>
            <div class="item">
                <span class="img_size"><img src="./img/<?php print $cart_item["item_id"];?>/<?php print $cart_item["img"];?>"></span>
                <span><?php print htmlspecialchars($cart_item["name"], ENT_QUOTES, 'UTF-8');?></span>
                <span><?php print htmlspecialchars($cart_item["price"], ENT_QUOTES, 'UTF-8');?>円</span>
                <span><?php print htmlspecialchars($cart_item["amount"], ENT_QUOTES, 'UTF-8');?>個</span>
                <span>合計:<?php print htmlspecialchars($cart_item["price"] * $cart_item["amount"], ENT_QUOTES, 'UTF-8');?>円</span>
            </div>
        <?php }?>
        <div class="clear">
    </div>
</body>
</html>