<?php

namespace backend\widgets;


use backend\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;

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
                $items = ['label' => $menu->label, 'items'=>[]];
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
            $menuItems[] = ['label' => '注销('.\Yii::$app->user->identity->username.')', 'url' => ['admin/logout']];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }

}