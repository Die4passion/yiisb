<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class IndexController extends Controller
{
    //定义变量判断购物车更新还是新增
    public $update = false;

    //首页
    public function actionIndex()
    {
        return $this->render('index');
    }

    //商品列表页
    public function actionList($id)
    {
        $ids[] = $id;
        $ids2[] = $id;
        $goods_category = GoodsCategory::findOne(['id' => $id]);
        $children = $goods_category->children()->all();
        //找出所有下级分类的id
        foreach ($children as $child) {
            $ids[] = $child->id;
        }
        $models = Goods::findAll(['goods_category_id' => $ids]);
        //找到当前分类的所有上级分类
        $parents = $goods_category->parents()->all();
        foreach ($parents as $parent) {
            $ids2[] = $parent->id;
        }
        $categories = GoodsCategory::findAll(['id' => $ids2]);
        return $this->render('list', ['models' => $models, 'categories' => $categories]);
    }

    //商品详情页
    public function actionGoods($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $goods_category = GoodsCategory::findOne(['id' => $model->goods_category_id]);
        $ids[] = $model->goods_category_id;
        $parents = $goods_category->parents()->all();
        foreach ($parents as $parent) {
            $ids[] = $parent->id;
        }
        $categories = GoodsCategory::findAll(['id' => $ids]);
        return $this->render('goods', ['model' => $model, 'categories' => $categories]);
    }

    //加入购物车
    public function actionAddCart()
    {
        //改变数据
        self::changeCart();
        //将当前地址get传过去
        $name = \Yii::$app->request->post('name');
        $name = urlencode($name);
        //跳转到购物车页面
        return $this->redirect(['index/cart?name=' . $name]);
    }

    //更新购物车
    public function actionUpdateCart()
    {
        $this->update = true;
        self::changeCart();
    }

    //购物车页面
    public function actionCart()
    {
        if (\Yii::$app->user->isGuest) {
            //取出cookie中的商品id和数量
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $goods_id => $amount) {
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        } else {
            //先将缓存同步到数据库
            self::cookie2data();
            $models = self::getCarts();
        }
        $name = \Yii::$app->request->get('name');
        return $this->render('cart', ['models' => $models, 'name' => $name]);
    }

    //购物车回退
    public function actionBack2Goods()
    {
        $name = \Yii::$app->request->get('name');
        //跳到上一个页面
        if ($name) {
            return $this->redirect($name);
        }
        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    //将购物车cookie同步到数据表
    private function cookie2data()
    {
        //获取cookie中的购物车数据
        $cookies = \Yii::$app->request->cookies;
        $cookie = $cookies->get('cart');
        $member_id = \Yii::$app->user->getId();
        //将所有的缓存读出来，分别判断数据表是否有值，有则合并到数据表，没有则新增到数据表
        if ($cookie) {
            $cart = unserialize($cookie->value);
            foreach ($cart as $goods_id => $amount) {
                $old_cart = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
                if ($old_cart != null) {
                    $old_cart->amount += $amount;
                    $old_cart->save();
                } else {
                    $model = new Cart();
                    $model->setAttributes(['member_id' => $member_id, 'goods_id' => $goods_id, 'amount' => $amount]);
                    $model->save();
                }
            }
        }
        //清空cookie
        $cookie = new Cookie([
            'name' => 'cart',
            'value' => '',
            'expire' => time() - 1,
        ]);
        \Yii::$app->response->cookies->add($cookie);
    }

    //更新购物车的方法
    private function changeCart()
    {
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        //如果商品不存在，提示错误信息
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        //如果未登陆 保存到cookie
        if (\Yii::$app->user->isGuest) {
            //获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //如果cookie中没用数据，定义为空数组
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            //将商品id和数量保存到cookie
            $cookies = \Yii::$app->response->cookies;
            //更新
            if ($this->update) {
                //如果不是0这直接改为这个数，如果小于0这删掉这一条
                if ($amount > 0) {
                    $cart[$goods_id] = $amount;
                } else {
                    if (key_exists($goods['id'], $cart)) unset($cart[$goods_id]);
                }
                //修改
            } else {
                //检查购物车中是否有该商品，有则累加
                if (key_exists($goods->id, $cart)) {
                    $cart[$goods_id] += $amount;
                } else {
                    $cart[$goods_id] = $amount;
                }
            }
            //保存到cookie
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => serialize($cart),
                'expire' => time() + 24 * 3600,
            ]);
            $cookies->add($cookie);
            //如果登陆 保存到数据表
        } else {
            //登陆先查cookie里是否有商品
            //没有直接保存到数据库
            $member_id = \Yii::$app->user->getId();
            //判断数据库是否已经有这个商品和对应的memberid数据
            $old_cart = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
            //如果是更新直接修改，如果amount 小于 0 直接删除
            if ($this->update) {
                if ($old_cart == null) {
                    throw new NotFoundHttpException('你的购物车还没有该商品');
                }
                if ($amount > 0) {
                    $old_cart->amount = $amount;
                    $old_cart->save();
                } else {
                    $old_cart->delete();
                }
            } else {
                //如果有则加上之前的数据
                if ($old_cart != null) {
                    $old_cart->amount += $amount;
                    $old_cart->save();
                } else {
                    //其他情况直接保存这条数据
                    $model = new Cart();
                    $model->setAttributes(['member_id' => $member_id, 'goods_id' => $goods_id, 'amount' => $amount]);
                    $model->save();
                }
            }
            self::cookie2data();
        }
    }

    //找到用户所有购物车的方法
    private function getCarts()
    {
        //定义一个空数组接收数据
        $models = [];
        //找到该用户所有的商品
        $member_id = \Yii::$app->user->getId();
        $carts = Cart::find()->where(['member_id' => $member_id])->andWhere(['>', 'amount', 0])->all();
        //将所有的购物车数据遍历出来并保存到数组里
        foreach ($carts as $cart) {
            $goods = Goods::findOne(['id' => $cart->goods_id])->attributes;
            $goods['amount'] = $cart->amount;
            $models[] = $goods;
        }
        return $models;
    }

    //将购物车加入订单
    public function actionOrder()
    {
        //未登录跳到登陆页面
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['user/login', 'name' => \Yii::$app->request->getUrl()]);
        } else {
            $member_id = \Yii::$app->user->getId();
            //找到所有收货地址
            $addresses = Address::find()->where(['member_id' => $member_id])->asArray()->all();
            //找到所有购物车
            $carts = self::getCarts();
            //如果没有购物车跳转到首页
            if ($carts == null) {
                return $this->goHome();
            }
            return $this->render('order', ['addresses' => $addresses, 'carts' => $carts]);
        }
    }

    //订单提交
    public function actionPayment()
    {
        if (\Yii::$app->request->isPost) {
            $address_id = \Yii::$app->request->post('address_id');
            $payment_id = \Yii::$app->request->post('payment_id');
            $delivery_id = \Yii::$app->request->post('delivery_id');
            $price = \Yii::$app->request->post('price');
            $member_id = \Yii::$app->user->getId();
            //找到所有地址
            $address = Address::findOne(['id' => $address_id, 'member_id' => $member_id]);
            if ($address == null) {
                throw new NotFoundHttpException('地址不存在,请添加地址');
            }
            $order = new Order();
            //字段赋值
            $order->member_id = $member_id;
            $order->name = $address->name;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->area = $address->area;
            $order->address = $address->address;
            $order->tel = $address->tel;
            $order->delivery_id = $delivery_id;
            $order->delivery_name = Order::$delivery_style[$delivery_id]['name'];
            $order->delivery_price = Order::$delivery_style[$delivery_id]['price'];
            $order->payment_id = $payment_id;
            $order->payment_name = Order::$payment_style[$payment_id]['name'];
            $order->total = $price;
            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            $e = '1';
            try {
                $order->save();
                //订单商品详情表
                $carts = Cart::findAll(['member_id' => $member_id]);
                foreach ($carts as $cart) {
                    $goods = Goods::findOne(['id' => $cart->goods_id, 'status' => 1]);
                    //商品没有了，抛出异常
                    if ($goods == null) {
                        throw new Exception($goods->name . '--已售完');
                    }
                    //库存不足抛出异常
                    if ($goods->stock < $cart->amount) {
                        throw new Exception($goods->name . '--库存不足');
                    }
                    //构造生成订单详情表
                    $order_goods = new OrderGoods();
                    $order_goods->member_id = $member_id;
                    $order_goods->order_id = $order->id;
                    $order_goods->goods_id = $cart->goods_id;
                    $order_goods->goods_name = $goods->name;
                    $order_goods->logo = $goods->logo;
                    $order_goods->price = $goods->shop_price;
                    $order_goods->amount = $cart->amount;
                    $order_goods->total = $order_goods->amount * $order_goods->price;
                    //保存订单详情表
                    $order_goods->save();
                    $goods->stock -= $cart->amount;
                    $goods->save();
                }
                //清空购物车
                Cart::deleteAll(['member_id' => $member_id]);
                //一切正常，事务提交
                $transaction->commit();
            } catch (Exception $e) {
                //回滚
                $transaction->rollBack();
            }
            if ($e === '1') {
                return $e;
            } else {
                return $e->getMessage();
            }
        }
    }

    //提交成功页面
    public function actionOrderSave()
    {
        return $this->render('order-success');
    }

    //订单状态页面
    public function actionOrderStatus()
    {
        //显示该用户的所有订单
        $member_id = \Yii::$app->user->getId();
        $orders = OrderGoods::findAll(['member_id' => $member_id]);
        return $this->render('order-status', ['orders' => $orders]);
    }

    //测试方法
    public function actionTest()
    {

    }

    public function actionSearch()
    {
        $query = Goods::find();
        if ($keyword = \Yii::$app->request->get('key')) {
//				var_dump($keyword);exit;
            $cl = new SphinxClient();
            $cl->SetServer('127.0.0.1', 9312);
            $cl->SetConnectTimeout(10);
            $cl->SetArrayResult(true);
            $cl->SetMatchMode(SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            if (!isset($res['matches'])) {
//                throw new NotFoundHttpException('没有找到xxx商品');
                $query->where(['id' => 0]);
            } else {
                $ids = ArrayHelper::map($res['matches'], 'id', 'id');
                $query->where(['id' => $ids]);
            }
        }
        $models = $query->all();
        $keywords = array_keys($res['words']);
        $options = array(
            'before_match' => '<span style="color:#ff1900;">',
            'after_match' => '</span>',
            'chunk_separator' => '...',
            'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
        );
        foreach ($models as $index => $item) {
            $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
            $models[$index]->name = $name[0];
        }
        return $this->render('search',['models'=>$models]);
    }
}