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

        /**
         * カートからの削除を行う。
         * @param btn_id ボタンID
         * @param item_id 商品ID
         */
        function removeCart(btn_id, item_id) {
            var formObj = $("#remoce_cart_form");
            setActionKind(formObj, btn_id);
            $("#item_id").val(item_id);
            formObj.submit();
        }
    </script>
</head>
<body>
    <?php require_once "./view/common/menu.php";?>
    <h1>カート画面</h1>
    <a href="./item_list_controller.php">商品一覧へ</a>
    <form id="remoce_cart_form" method="POST">
        <input type="hidden" id="item_id" name="item_id" value="">
    </form>
    <div>
        <form method="POST">
            合計金額:<?php print $total_price;?> 円
            <?php if(count($cart_item_list) > 0) {?>
                <input type="button" id="buy_item" value="購入する">
            <?php }?>
        </form>
    </div>
    <?php require_once "./view/common/message.php";?>
    <div class="flex">
        <?php foreach($cart_item_list as $cart_item) { ?>
            <div class="item">
                <span class="img_size"><img src="./img/<?php print $cart_item["item_id"];?>/<?php print $cart_item["img"];?>"></span>
                <span><?php print htmlspecialchars($cart_item["name"], ENT_QUOTES, 'UTF-8');?></span>
                <span><?php print $cart_item["price"];?>円</span>
                <span><?php print $cart_item["amount"];?>個</span>
                <span>小計:<?php print $cart_item["price"] * $cart_item["amount"];?>円</span>
                <span><input type="button" id="remove_cart" value="カートから削除" onclick="removeCart(this.id, <?php print $cart_item['item_id'];?>);"></span>
            </div>
            <div class="clear">
        <?php }?>
    </div>
    
</body>
</html>