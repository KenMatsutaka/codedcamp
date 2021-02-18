<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>購入完了画面</title>
    <style>
        .flex {
            width: 600px;
        }

        .flex .item {
            //border: solid 1px;
            width: 120px;
            height: 260px;
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
    <h1>購入完了画面</h1>
    <?php require_once "./view/common/message.php";?>
    <div>商品の購入が完了しました。</div>
    合計金額:<?php print $total_price;?> 円
    <div class="flex">
        <?php foreach($buy_item_list as $buy_item) { ?>
            <div class="item">
                <span class="img_size"><img src="./img/<?php print $buy_item["item_id"];?>/<?php print $buy_item["img"];?>"></span>
                <span><?php print htmlspecialchars($buy_item["name"], ENT_QUOTES, 'UTF-8');?></span>
                <span><?php print $buy_item["buy_price"];?>円</span>
                <span><?php print $buy_item["amount"];?>個</span>
                <span>小計:<?php print $buy_item["buy_price"] * $buy_item["amount"];?>円</span>
            </div>
        <?php }?>
        <div class="clear">
    </div>

</body>
</html>