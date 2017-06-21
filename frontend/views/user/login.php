<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?= \yii\helpers\Html::img('@web/images/logo.png') ?></a></h2>
    </div>
</div>
<!-- 页面头部 end -->
<!-- 登录主体部分start -->
<div class="login w990 bc mt10">
    <div class="login_hd">
        <h2>用户登录</h2>
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
                <?= $form->field($model, 'username')->textInput(['class' => 'txt', 'placeholder' => '请输入你的昵称/手机号/邮箱~'])->label('用户名 ：'); ?>
                <?= $form->field($model, 'password')->passwordInput(['class' => 'txt', 'placeholder' => '请输入你的密码'])->label('密码 ：'); ?>
                <?= $form->field($model, 'captcha', ['options' => ['class' => 'checkcode']])->widget(\yii\captcha\Captcha::className(), ['template' => '{input}{image}'])->label('验证码： '); ?>
                <?= $form->field($model, 'remember')->checkbox(['class' => 'chb', 'uncheck' => null], $enclosedByLabel = false)->label('&nbsp;')->hint('记住我的登录状态~~~', ['tag' => null]); ?>
                <li>
                    <label for="">&nbsp;</label>
                    <input type="submit" value="" class="login_btn"/>
                </li>
            </ul>
            <?php \yii\widgets\ActiveForm::end(); ?>

            <div class="coagent mt15">
                <dl>
                    <dt>使用合作网站登录商城：</dt>
                    <dd class="qq"><a href=""><span></span>QQ</a></dd>
                    <dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
                    <dd class="yi"><a href=""><span></span>网易</a></dd>
                    <dd class="renren"><a href=""><span></span>人人</a></dd>
                    <dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
                    <dd class="baidu"><a href=""><span></span>百度</a></dd>
                    <dd class="douban"><a href=""><span></span>豆瓣</a></dd>
                </dl>
            </div>
        </div>

        <div class="guide fl">
            <h3>还不是1024会员</h3>
            <p>现在免费获取邀请码成为1024用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

            <a href="regist.html" class="reg_btn">免费注册 >></a>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->