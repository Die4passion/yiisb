<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/style/fillin.css');
$this->registerJsFile('@web/js/cart2.js');
?>
    <div style="clear:both;"></div>

    <!-- 页面头部 start -->
    <div class="header w990 bc mt15">
        <div class="logo w990">
            <h2 class="fl"><?= \yii\helpers\Html::a(\yii\helpers\Html::img('@web/images/logo.png'), \yii\helpers\Url::to(['index/index']))?></h2>
            <div class="flow fr flow2">
                <ul>
                    <li>1.我的购物车</li>
                    <li class="cur">2.填写核对订单信息</li>
                    <li>3.成功提交订单</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 页面头部 end -->

    <div style="clear:both;"></div>

    <!-- 主体部分 start -->
    <div class="fillin w990 bc mt15">
        <div class="fillin_hd">
            <h2>填写并核对订单信息</h2>
        <div class="fillin_bd">
            <!-- 收货人信息  start-->
            <div class="address">
                <h3>收货人信息</h3>
                <div class="address_info">
                    <?php foreach ($addresses as $address): ?>
                        <p><input type="radio" value="<?= $address['id'] ?>"
                                  name="address_id" <?= $address['is_default'] ? 'checked' : '' ?>/>
                            <?= $address['name'] . '&emsp;' . $address['tel'] . '&emsp;' . $address['province'] . '&emsp;' . $address['city'] . '&emsp;' . $address['area'] . '&emsp;' . $address['address'] ?>
                        </p>
                    <?php endforeach; ?>
                    <p><?= \yii\helpers\Html::a('添加收货人', ['address/index'])?></p>
                </div>

            </div>
            <!-- 收货人信息  end-->

            <!-- 配送方式 start -->
            <div class="delivery">
                <h3>送货方式 </h3>


                <div class="delivery_select">
                    <table>
                        <thead>
                        <tr>
                            <th class="col1">送货方式</th>
                            <th class="col2">运费</th>
                            <th class="col3">运费标准</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (\frontend\models\Order::$delivery_style as $k => $value): ?>
                            <tr <?= $k ? '' : 'class="cur"' ?>>
                                <td>
                                    <input type="radio" name="delivery" <?= $k ? '' : 'checked' ?>
                                           value="<?= $k + 1 ?>"/><?= $value['name'] ?>
                                </td>
                                <td>￥<?= $value['price'] ?>.00</td>
                                <td><?= $value['description'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- 配送方式 end -->

            <!-- 支付方式  start-->
            <div class="pay">
                <h3>支付方式 </h3>


                <div class="pay_select">
                    <table>
                        <?php foreach (\frontend\models\Order::$payment_style as $k => $value): ?>
                            <tr <?= $k ? '' : 'class="cur"' ?>>
                                <td class="col1">
                                    <input type="radio" name="pay"
                                           value="<?= $k + 1 ?>" <?= $k ? '' : 'checked' ?>/><?= $value['name'] ?>
                                </td>
                                <td class="col2"><?= $value['description'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                </div>
            </div>
            <!-- 支付方式  end-->

            <!-- 发票信息 start-->
            <div class="receipt none">
                <h3>发票信息 </h3>


                <div class="receipt_select ">
                    <form action="">
                        <ul>
                            <li>
                                <label for="">发票抬头：</label>
                                <input type="radio" name="type" checked="checked" class="personal"/>个人
                                <input type="radio" name="type" class="company"/>单位
                                <input type="text" class="txt company_input" disabled="disabled"/>
                            </li>
                            <li>
                                <label for="">发票内容：</label>
                                <input type="radio" name="content" checked="checked"/>明细
                                <input type="radio" name="content"/>办公用品
                                <input type="radio" name="content"/>体育休闲
                                <input type="radio" name="content"/>耗材
                            </li>
                        </ul>
                    </form>

                </div>
            </div>
            <!-- 发票信息 end-->

            <!-- 商品清单 start -->
            <div class="goods">
                <h3>商品清单</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="col1">商品</th>
                        <th class="col3">价格</th>
                        <th class="col4">数量</th>
                        <th class="col5">小计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 0 ?>
                    <?php foreach ($carts as $k => $cart): ?>
                        <tr>
                            <td class="col1"><?= \yii\helpers\Html::a(\yii\helpers\Html::img($cart['logo']), ['index/goods', 'id' => $cart['id']]) ?>
                                <strong><?= \yii\helpers\Html::a($cart['name'], ['index/goods', 'id' => $cart['id']]) ?></strong>
                            </td>
                            <td class="col3">￥<?= $cart['shop_price'] ?>.00</td>
                            <td class="col4"> <?= $cart['amount'] ?></td>
                            <td class="col5">￥<span><?= $cart['shop_price'] * $cart['amount'] ?>.00</span></td>
                        </tr>
                        <?php
                        $num += $cart['amount'];endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <ul id="total_money">
                                <li>
                                    <span><?= $num ?> 件商品，总商品金额：</span>
                                    ￥ <em>0.00</em>
                                </li>
                                <li>
                                    <span>运费：</span>
                                    ￥ <em>0.00</em>
                                </li>
                                <li>
                                    <span>应付总额：</span>
                                    ￥ <em>0.00</em>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- 商品清单 end -->

        </div>

        <div class="fillin_ft">
            <a href="javascript:void(0);" id="submit_button"></a>
            <p>应付总额：<strong>￥0.00元</strong></p>

        </div>
    </div>
    <!-- 主体部分 end -->

    <div style="clear:both;"></div>

<?php
//用ajax传值还有一个问题，如何回显php的错误异常
$url = \yii\helpers\Url::to(['index/payment']);
$url1 = \yii\helpers\Url::to(['index/cart']);
$url2 = \yii\helpers\Url::to(['index/order-save']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    var total = 0;
    $(".col5 span").each(function(){
        total += parseFloat($(this).text());
    });
    //购物总额
    $('#total_money').find('li:first').find('em').text(total.toFixed(2));
    //点击运货方式改变价格
    $('[name="delivery"]').on('change',function() {
        var freight = parseFloat($(this).closest('tr').find('td:eq(1)').text().substr(1));
        dototal(freight);
    });
    $(function() {
        var freight = parseFloat($('[name="delivery"]:checked').closest('tr').find('td:eq(1)').text().substr(1));
        dototal(freight);
    });
    function dototal(freight) {
        $('#total_money').find('li:eq(1)').find('em').text(freight.toFixed(2));
        pay = total + parseFloat($('#total_money').find('li:eq(1)').find('em').text());
        $('#total_money').find('li:last').find('em').text(pay.toFixed(2));
        $('.fillin_ft').find('strong').text('￥'+pay.toFixed(2)+'元');
    }
    $('#submit_button').click(function() {
        var delivery=$('[name="delivery"]:checked').val() - 1;
        var payment=$('[name="pay"]:checked').val() - 1;
        var address=$('[name="address_id"]:checked').val() - 1;
    $.post("$url",{address_id:address, payment_id:payment, delivery_id:delivery, price:pay,"_csrf-frontend":"$token"},function(response) {
            if(response !== '1'){
                alert(response);
                window.location.href = "$url1";
            } else {
                window.location.href = "$url2";
            }
        });
    });
JS

));

