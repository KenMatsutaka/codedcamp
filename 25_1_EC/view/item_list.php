<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "./view/common/head.php";?>
    <title>商品一覧画面</title>
    <script>
        $(function (){});
        function clickChartBtn(actionKind, itemId) {
            var formObj = $("#cart_form");
            setActionKind(formObj, actionKind);
            $("#item_id").val(itemId);
            formObj.submit();
        }
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
    <?php require_once "./view/common/message.php";?>
    <form id="cart_form" method="POST">
        <input type="hidden" id="item_id" name="item_id">
    </form>
    <div class="flex">
        <?php foreach($open_item_list as $open_item) { ?>
            <div class="item">
                <span class="img_size"><img src="./img/1/test.jpg"></span>
                <span><?php print htmlspecialchars($open_item["name"], ENT_QUOTES, 'UTF-8');?></span>
                <span><?php print htmlspecialchars($open_item["price"], ENT_QUOTES, 'UTF-8');?>円</span>
                <?php if($open_item["stock"] === "0") {?>
                    <span class="red">売り切れ</span>
                <?php } else {?>
                    <?php if($open_item["cart_count"] === "0") {?>
                        <input type="button" name="add_cart" value="カートに追加" onclick="clickChartBtn(this.name, <?php print $open_item['id'];?>);">
                    <?php } else { ?>
                        <input type="button" name="remove_cart" value="カートから削除"  onclick="clickChartBtn(this.name, <?php print $open_item['id'];?>);">
                    <?php }?>
                <?php }?>
            </div>
        <?php }?>
    </div>
</body>
</html>