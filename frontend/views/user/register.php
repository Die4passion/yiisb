<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?= \yii\helpers\Html::img('@web/images/logo.png') ?></a></h2>
    </div>
</div>
<!-- 页面头部 end -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2><?=$model->isNewRecord? '注册1024商城':'修改用户信息' ?></h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">

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
                <?= $form->field($model, 'username')->textInput(['class' => 'txt', 'placeholder' => '取一个炫酷的名字吧！'])->label('用户名 ：'); ?>
                <?php if($model->isNewRecord):?>
                <?= $form->field($model, 'password')->passwordInput(['class' => 'txt', 'placeholder' => '密码不要太简单哦~'])->label('密码 ：'); ?>
                <?= $form->field($model, 're_password')->passwordInput(['class' => 'txt', 'placeholder' => '请确认你的密码不要输错哦~'])->label('确认密码 ：'); ?>
                <?php endif;?>
                <?= $form->field($model, 'email')->textInput(['class' => 'txt', 'placeholder' => '推荐使用Gmail或QQ邮箱'])->label('邮箱 ：'); ?>
                <?= $form->field($model, 'tel')->textInput(['class' => 'txt', 'placeholder' => '请输入11位中国大陆手机号'])->label('手机号 ：'); ?>
                <?php if($model->isNewRecord):?>
                    <?= $form->field($model,'sms_captcha',['options'=>['class'=>'checkcode']])->textInput(['class'=>'txt','id'=>'sms_input','disabled'=>true])->hint(\yii\helpers\Html::button('发送短信验证码',['id'=>'send_sms_button']), ['tag' => null])->label('短信验证码：') ?>
                <?php endif;?>
                <?= $form->field($model, 'captcha', ['options' => ['class' => 'checkcode']])->widget(\yii\captcha\Captcha::className(), ['template' => '{input}{image}'])->label('验证码： '); ?>
                <?= $form->field($model, 'agree')->checkbox(['class' => 'chb', 'uncheck' => null], $enclosedByLabel = false)->label('&nbsp;')->hint('我已年满18岁并遵守《MIT licence》', ['tag' => null]); ?>


                <li>
                    <label for="">&nbsp;</label>
                    <input type="submit" value="" class="login_btn"/>
                </li>
            </ul>
            <?php \yii\widgets\ActiveForm::end(); ?>

        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<script>
    $("#send_sms_button").click(function(){
        //发送验证码按钮被点击时
        //手机号
        var tel = $("#member-tel").val();
        //AJAX post提交tel参数到 user/send-sms
        $.post('<?= \yii\helpers\Url::to(['user/send-sms']) ?>',{tel:tel},function(data){
            if(data == 'success'){
                console.log('短信发送成功');
//                alert('短信发送成功');
                $('#sms_input').prop('disabled',false);

                var time=60;
                var interval = setInterval(function(){
                    time--;
                    if(time<=0){
                        clearInterval(interval);
                        var html = '获取验证码';
                        $('#send_sms_button').prop('disabled',false);
                    } else{
                        var html = time + ' 秒后再次获取';
                        $('#send_sms_button').prop('disabled',true);
                    }
                    $('#send_sms_button').html(html);
                },1000);
            }else{
                console.log(data);
            }
        });
    });
</script>