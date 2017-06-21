<?php

namespace backend\widgets;


use backend\models\Admin;
use backend\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use yii\helpers\Url;

class MenuWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        NavBar::begin([
            'brandLabel' => '新时代的我们',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [];
        if (\Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => \Yii::$app->user->loginUrl];
        } else {
            $menus = Menu::find()->where(['parent_id' => 0])->orderBy('sort')->all();
            foreach ($menus as $menu) {
                $items = ['label' => $menu->label, 'items' => []];
                //根据用户权限判断是否显示
                foreach ($menu->children as $child) {
                    //根据用户权限判断，是否显示该菜单
                    if (\Yii::$app->user->can($child->url)) {
                        $items['items'][] = ['label' => $child->label, 'url' => [$child->url]];
                    }
                }
                //如果该一级菜单有子菜单，就显示
                if ($items['items']) {
                    $menuItems[] = $items;
                }
            }
            //为什么前面的url不加斜杠可以访问，下面的url写成/admin/change-password就会直接追加到后面呢？
            $menuItems[] = ['label' => \Yii::$app->user->identity->username . ', 欢迎回来！', 'items' => [
                ['label' => '修改密码', 'url' => Url::to(['admin/change-password', 'id' => \Yii::$app->user->getId()])],
                ['label' => '更多信息', 'url' => 'http://blog.die4passion.com', 'linkOptions' => ['target' => '_blank']],
                ['label' => '注销', 'url' => '/admin/logout'],
            ]];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }

}