<?php

namespace frontend\widgets;

use backend\models\GoodsCategory;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class GoodsCategoryWidget extends Widget
{
    //是否展开商品分类,
    //默认不展开，只在首页设为true
    public $unfold = false;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        //导航图片
        $logo = Html::a(Html::img('@web/images/logo.png'), Url::to(['index/index']));
        //登陆选择
        if (\Yii::$app->user->isGuest) {
            $user = '您好，请' . Html::a('登录', Url::to(['user/login',"name"=>\Yii::$app->request->getUrl()]));
        } else {
            $user = \Yii::$app->user->identity->username . '欢迎回来！' . Html::a('安全退出', Url::to(['user/logout']));
        }
        //地址
        $address = Html::a('收货地址>', Url::to(['address/index']));
        //去购物车
        $cart = Html::a('去购物车结算', Url::to(['index/cart']));
        //订单
        $order = Html::a('我的订单>', Url::to(['index/order']));
        $html1 = <<<HTML
<div class="header w1210 bc mt15">
    <!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
    <div class="logo w1210">
        <h1 class="fl">{$logo}</h1>
        <!-- 头部搜索 start -->
        <div class="search fl">
            <div class="search_form">
                <div class="form_left fl"></div>
                <form action="" name="search" method="get" class="fl">
                    <input type="text" class="txt" value="请输入商品关键字" />
                    <input type="submit" class="btn" value="搜索" />
                </form>
                <div class="form_right fl"></div>
            </div>

            <div style="clear:both;"></div>

            <div class="hot_search">
                <strong>热门搜索:</strong>
                <a href="">D-Link无线路由</a>
                <a href="">休闲男鞋</a>
                <a href="">TCL空调</a>
                <a href="">耐克篮球鞋</a>
            </div>
        </div>
        <!-- 头部搜索 end -->

        <!-- 用户中心 start-->
        <div class="user fl">
            <dl>
                <dt>
                    <em></em>
                    <a href="">用户中心</a>
                    <b></b>
                </dt>
                <dd>
                    <div class="prompt">
                        {$user}
                    </div>
                    <div class="uclist mt10">
                        <ul class="list1 fl">
                            <li><a href="">用户信息></a></li>
                            <li>{$order}</li>
                            <li>{$address}</li>
                            <li><a href="">我的收藏></a></li>
                        </ul>

                        <ul class="fl">
                            <li><a href="">我的留言></a></li>
                            <li><a href="">我的红包></a></li>
                            <li><a href="">我的评论></a></li>
                            <li><a href="">资金管理></a></li>
                        </ul>

                    </div>
                    <div style="clear:both;"></div>
                    <div class="viewlist mt10">
                        <h3>最近浏览的商品：</h3>
                        <ul>
                            <li><a href=""><?= Html::img('@web/images/view_list1.jpg') ?></a></li>
                            <li><a href=""><?= Html::img('@web/images/view_list2.jpg') ?></a></li>
                            <li><a href=""><?= Html::img('@web/images/view_list3.jpg') ?></a></li>
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
        <!-- 用户中心 end-->

        <!-- 购物车 start -->
        <div class="cart fl">
            <dl>
                <dt>
                    {$cart}
                    <b></b>
                </dt>
                <dd>
                    <div class="prompt">
                        购物车中还没有商品，赶紧选购吧！
                    </div>
                </dd>
            </dl>
        </div>
        <!-- 购物车 end -->
    </div>
    <!-- 头部上半部分 end -->

    <div style="clear:both;"></div>

    <!-- 导航条部分 start -->
    <div class="nav w1210 bc mt10">
        <!--  商品分类部分 start-->
HTML;

        //使用缓存
        $cache = \Yii::$app->cache;
        $id = 'goods_category' . $this->unfold;
        //如果缓存有直接返回
        $html2 = $cache->get($id);
        if (!$html2) {
            $html2 = '';
            //根据是否展开判断class
            $cat1 = $this->unfold ? '' : 'cat1';
            $none = $this->unfold ? '' : 'none';
            //获取所有的一级分类
            $categories = GoodsCategory::find()->roots()->all();
            //遍历一级分类
            foreach ($categories as $k => $category) {
                $html2 .= '<div class="cat ' . ($k == 0 ? 'itme1' : '') . '">
<h3>' . Html::a($category->name, ['index/list', 'id' => $category->id]) . '<b></b></h3><div class="cat_detail">';
                //遍历所有二级分类
                foreach ($category->children as $child) {
                    $html2 .= '<dl class="dl_1st"><dt>' . Html::a($child->name, ['index/list', 'id' => $child->id]) . '</dt><dd>';
                    //遍历所有三级分类
                    foreach ($child->children as $kid) {
                        $html2 .= Html::a($kid->name, ['index/list', 'id' => $kid->id]);
                    }
                    $html2 .= '</dd></dl>';
                }
                $html2 .= '</div></div>';
            }
            //不在首页添加cat1类
            $html2 = <<<HTML
        <div class="category fl {$cat1}">
            <div class="cat_hd">
                <h2>全部商品分类</h2>
                <em></em>
            </div>
            <div class="cat_bd {$none}">
                {$html2}
            </div>
        </div>
HTML;
//        将查出来的数据保存到缓存
            $cache->set($id, $html2);
        }
        //导航最后
        $html3 = <<<HTML
        <!--  商品分类部分 end-->

        <div class="navitems fl">
            <ul class="fl">
                <li class="current"><a href="">首页</a></li>
                <li><a href="">电脑频道</a></li>
                <li><a href="">家用电器</a></li>
                <li><a href="">品牌大全</a></li>
                <li><a href="">团购</a></li>
                <li><a href="">积分商城</a></li>
                <li><a href="">夺宝奇兵</a></li>
            </ul>
            <div class="right_corner fl"></div>
        </div>
    </div>
    <!-- 导航条部分 end -->

</div>
HTML;
//        导航条的所有内容
        $html = $html1 . $html2 . $html3;
        //返回到页面
        //var_dump($html);exit();
        return $html;
    }
}