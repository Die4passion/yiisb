<?php

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class HeadWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if (\Yii::$app->user->isGuest) {
            $is_guest = '<li>您好，欢迎来到草榴社区！[' . Html::a("登录", Url::to(["user/login", "name" => \Yii::$app->request->getUrl()])) . '] [' . Html::a("免费注册", Url::to(["user/register", "name" => \Yii::$app->request->getUrl()])) . ']&emsp;
                        [<a href="register.html">获取邀请码</a>]
                    </li>';
        } else {
            $is_guest = '<li>' . \Yii::$app->user->identity->username . '，欢迎回来！[' . Html::a("安全退出", Url::to(["user/logout"])) . ']</li>
                        <li class="line">|</li>
                        <li> ' . Html::a("我的订单", Url::to(["index/order"])) . ' </li>
                        <li class="line">|</li>
                        <li>客户服务</li>';
        }
        $html = <<<HTML
        <div class="topnav">
            <div class="topnav_bd w990 bc">
                <div class="topnav_left"></div>
                <div class="topnav_right fr">
                    <ul>
                        {$is_guest}
                    </ul>
                </div>
            </div>
        </div>
HTML;

        return $html;
    }


}