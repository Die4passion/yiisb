<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order_goods".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $member_id
 * @property string $goods_name
 * @property string $logo
 * @property string $price
 * @property integer $amount
 * @property string $total
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'amount', 'member_id'], 'integer'],
            [['price', 'total'], 'number'],
            [['goods_name', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单ID',
            'goods_id' => '商品id',
            'goods_name' => '商品名称',
            'logo' => '商品图片',
            'price' => '单价',
            'amount' => '数量',
            'total' => '小计',
        ];
    }

    //保存订单详情
//    public function saveOrders($order_id, $member_id)
//    {
//        $carts = Cart::find()->where(['member_id'=>$member_id])->all();
//        foreach ($carts as $cart) {
//            $this->member_id = $member_id;
//            $this->order_id = $order_id - 0;
//            $this->goods_id = $cart->goods_id;
//            $this->goods_name = $cart->goods->name;
//            $this->logo = $cart->goods->logo;
//            $this->price = $cart->goods->shop_price;
//            $this->amount = $cart->amount;
//            $this->total = $cart->amount*$cart->goods->shop_price;
//            $this->save(false);
//            $this->isNewRecord = true;
//            $this->id = null;
//        }
//        return true;
//    }

    //对应的订单表
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
