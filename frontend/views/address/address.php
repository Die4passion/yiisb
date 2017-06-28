<div style="clear:both;"></div>

<!-- 头部 start -->
<?= \frontend\widgets\GoodsCategoryWidget::widget(); ?>
<!-- 头部 end-->

<div style="clear:both;"></div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach ($addresses as $k => $address): ?>
                <dl>
                    <dt><?= $k + 1 . '.' . $address->name . ' ' . $address->province. ' ' . $address->city . ' ' . $address->area . ' ' . $address->address . ' ' . $address->tel ?></dt>
                    <dd>
                        <?= \yii\helpers\Html::a('修改', \yii\helpers\Url::to(['address/update', 'id' => $address->id])) ?>
                        <?= \yii\helpers\Html::a('删除', \yii\helpers\Url::to(['address/del', 'id' => $address->id])) ?>
                        <?php if ($address->is_default) : ?>
                            <span style="color: #ff5dd2">默认收货地址</span>
                        <?php else: ?>
                            <?= \yii\helpers\Html::a('设为默认地址', \yii\helpers\Url::to(['address/default', 'id' => $address->id])) ?>
                        <?php endif; ?>
                    </dd>
                </dl>
            <?php endforeach; ?>

        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php $form = \yii\widgets\ActiveForm::begin([
                'fieldConfig' => [
                    'options' => [
                        'tag' => 'li',
                    ],
                    'errorOptions' => [
                        'tag' => 'p',
                    ],
                ]
            ]); ?>
            <ul>
                <?= $form->field($model, 'name')->textInput(['class' => 'txt', 'placeholder' => '请输入收货人姓名~'])->label('收货人 ：'); ?>
                <li>
                    <label for="">所在地区：</label>
                    <select name="Address[province]" required="required" id="address_province">
                        <option value="">请选择省份</option>
                    </select>
                    <select name="Address[city]" required="required" id="address_city">
                        <option value="">请选择城市</option>
                    </select>
                    <select name="Address[area]" required="required" id="address_area">
                        <option value="">请选择区县</option>
                    </select>
                </li>
                <?= $form->field($model, 'address')->textInput(['class' => 'txt address', 'placeholder' => '请输入你的详细地址~'])->label('详细地址 ：'); ?>
                <?= $form->field($model, 'tel')->textInput(['class' => 'txt', 'placeholder' => '请输入你的手机号~'])->label('手机号码 ：'); ?>
                <?= $form->field($model, 'is_default')->checkbox(['class' => 'chb', 'uncheck' => null], $enclosedByLabel = false)->label('&nbsp;')->hint('设为默认地址~~~', ['tag' => null]); ?>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="submit" value="保存" class="btn" id="set_address"/>
                </li>
            </ul>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>

<!-- 底部导航 start -->
<?= \frontend\widgets\HelpWidget::widget(); ?>
<!-- 底部导航 end -->

<div style="clear:both;"></div>
<script type="text/javascript">

</script>
</body>
</html>
<?php
/**
 * @var \yii\web\View
 */
//ajax加载

/*$url = \yii\helpers\Url::to(['address/locations']);
$province = $model->province;
$city = $model->city;
$area = $model->area;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var data = {
            pid: 0
        };
        var c = "#address_province";
        getList(c, data);
    $("#address_province").change(function() {
        $("#address_city")[0].length = 1;
        $("#address_area")[0].length = 1;
        var thisid = $(this).val();
        if (!thisid) {
            return;
        }
        var data = {
            pid: thisid
        };
        var next = "#address_city";
        getList(next, data);
    });
    $("#address_city").change(function() {
        $("#address_area")[0].length = 1;
        var thisid = $(this).val();
        if (!thisid) {
            return;
        }
        var data = {
            pid: thisid
        };
        var next = "#address_area";
        getList(next, data);
    });
    function getList(thename, data) {
        $.ajax({
            type: "GET",
            url: "{$url}",
            data: data,
            async: false,
            success: function (response) {
                $(response).each(function (i, v) {
                var html = '<option value="' + v.id + '">' + v.name + '</option>';
                $(thename).append(html);
                //也可以回调函数里面判断是否有值；
            });
            }
        });
    };
    if("{$province}") {
        $("#address_province").val("{$province}");
    }
    if ("{$city}") {
        $("#address_province").change();$("#address_city").val("{$city}");
    }
    if ("{$area}") {
        $("#address_city").change();$("#address_area").val("{$area}");
    }
JS
));*/
//$js = '';
//if ($model->province) {
//    $js .= '$("#address_province").val(' . $model->province . ');';
//}
//if ($model->city) {
//    $js .= '$("#address_province").change();$("#address_city").val(' . $model->city . ');';
//}
//if ($model->area) {
//    $js .= '$("#address_city").change();$("#address_area").val(' . $model->area . ');';
//}
//$this->registerJs($js);
$this->registerJsFile('@web/js/address.js');
$province = $model->province;
$city = $model->city;
$area = $model->area;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //填充省的数据
    $(address).each(function (i, v) {
            var html = '<option value="' + v.name + '">' + v.name + '</option>';
            $("#address_province").append(html);
    });
    //切换（选中）省，读取该省对应的市，更新到市下拉框
    $("#address_province").change(function(){
        var province = $(this).val();//获取当前选中的省
        $("#address_city")[0].length = 1;
        $("#address_area")[0].length = 1;
        //获取当前省对应的市 数据
        $(address).each(function(){
            if(this.name == province){
                $(this.city).each(function (i, v) {
                    var html = '<option value="'+v.name+'">'+v.name+'</option>';
                    $("#address_city").append(html);
                });
            }
        });
        //将县的下拉框数据清空
    });
    //切换（选中）市，读取该市 对应的县，更新到县下拉框
    $("#address_city").change(function(){
        var city = $(this).val();//当前选中的城市
        $("#address_area")[0].length = 1;
        $(address).each(function(){
            if(this.name == $("#address_province").val()){
                $(this.city).each(function(){
                    if(this.name == city){
                        $(this.area).each(function(i,v){
                            var html = '<option value="'+v+'">'+v+'</option>';
                            $("#address_area").append(html);
                        });
                    }
                });
            }
        });
    });
    //数据回显
    if("{$province}") {
        $("#address_province").val("{$province}");
    }
    if ("{$city}") {
        $("#address_province").change();$("#address_city").val("{$city}");
    }
    if ("{$area}") {
        $("#address_city").change();$("#address_area").val("{$area}");
    }
JS
));
