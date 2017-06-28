<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/style/cart.css');
$this->registerCssFile('@web/style/success.css');
$this->registerJsFile('@web/js/cart1.js');
?>
    <div style="clear:both;"></div>

    <!-- 页面头部 start -->
    <div class="header w990 bc mt15">
        <div class="logo w990">
            <h2 class="fl"><a href="index.html"><img src="images/logo.png" alt="京西商城"></a></h2>
            <div class="flow fr">
                <ul>
                    <li class="cur">1.我的购物车</li>
                    <li>2.填写核对订单信息</li>
                    <li>3.成功提交订单</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 页面头部 end -->

    <div style="clear:both;"></div>

    <!-- 主体部分 start -->
    <div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <?php if ($models == null):?><div class="success_bd">
        <p><span></span>你还没有选购任何商品哦~~~</p>
        <p class="message">你的购物车空空如也...   <?= \yii\helpers\Html::a('继续购物', \yii\helpers\Url::home()) ?> <a href="">问题反馈</a></p><?php else: ?>
        <table>
                <thead>
                <tr>
                    <th class="col1">商品名称</th>
                    <th class="col3">单价</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                    <th class="col6">操作</th>
            </tr>
            </thead>
                <tbody>
                <?php foreach ($models as $model): ?>
                <tr data-goods_id="<?= $model['id'] ?>">
                        <td class="col1"><?= \yii\helpers\Html::a(\yii\helpers\Html::img($model['logo']), ['index/goods', 'id' => $model['id']]) ?>
                            <strong><?= \yii\helpers\Html::a($model['name'], ['index/goods', 'id' => $model['id']]) ?></strong>
                    </td>
                        <td class="col3">￥<span><?= $model['shop_price'] ?></span></td>
                        <td class="col4">
                            <a href="javascript:void(0);" class="reduce_num"></a>
                            <input type="text" name="amount" value="<?= $model['amount'] ?>" class="amount"/>
                            <a href="javascript:void(0);" class="add_num"></a>
                    </td>
                        <td class="col5">￥<span><?= $model['shop_price'] * $model['amount'] ?>.00</span></td>
                        <td class="col6"><a href="javascript:void(0);" class="del_goods">删除</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
                <tfoot>
            <tr>
                <td colspan="6">购物金额总计： <strong>￥ <span id="total">0.00</span></strong></td>
            </tr>
            </tfoot>
        </table>
        <div class="cart_btn w990 bc mt10"><?= \yii\helpers\Html::a('继续购物', ['index/back2-goods', 'name' => $name], ['class' => 'continue']) ?><?= \yii\helpers\Html::a('结 算', ['index/order'], ['class' => 'checkout']) ?></div>
        <?php endif; ?>
    </div>
    <!-- 主体部分 end -->
<?php
$url = \yii\helpers\Url::to(['index/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    var total = 0;
    $(".col5 span").each(function(){
        total += parseFloat($(this).text());
    });
    $("#total").text(total.toFixed(2));

    $('.reduce_num, .add_num').click(function() {
      var goods_id = $(this).closest('tr').attr('data-goods_id');
      var amount = $(this).parent().find('.amount').val();
      $.post("$url",{goods_id:goods_id, amount:amount, "_csrf-frontend":"$token"});
    });

    $(".del_goods").click(function() {
      if(confirm('你确认要删除？？？!')) {
          var goods_id = $(this).closest('tr').attr('data-goods_id');
          $.post("$url",{goods_id:goods_id, amount:0, "_csrf-frontend":"$token"});
          $(this).closest('tr').remove();
      }
    });
JS
));

