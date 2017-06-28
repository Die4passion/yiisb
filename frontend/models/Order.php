<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{

    //定义送货方式
    public static $delivery_style = [
        ['name' => '普通快递送货上门', 'price' => '10', 'description' => '安全放心送到家，良心推荐！'],
        ['name' => 'EMS 特别快', 'price' => '50', 'description' => '加急就选这个哦亲~全国24小时必达！'],
        ['name' => '店门自取', 'price' => '0', 'description' => '免费任性，附近有京西直营店的童鞋~~'],
    ];
    public static $payment_style = [
        ['name'=> '货到付款', 'description'=> '仅支持大城市'],
        ['name'=> '在线支付', 'description'=> '支持支付宝，微信，paypal等...'],
        ['name'=> '分期付款', 'description'=> '学生党首选！'],
    ];
    //定义状态
    public static $status_option = ['已取消', '待付款', '待发货', '待收货', '已完成',];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }

    //before save
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->create_time = time();
            $this->status = 1;
        }
        return parent::beforeSave($insert);
    }
    //保存订单
//    public function SaveOrder(array $data)
//    {
//        $address_id = $data['address_id'];
//        $payment_id = $data['payment_id'];
//        $delivery_id = $data['delivery_id'];
//        $price = $data['price'];
//        //找到所有地址
//        $address = Address::findOne(['id'=>$address_id]);
//        //字段赋值
//        $this->member_id = $data['member_id'];
//        $this->name = $address->name;
//        $this->province = $address->province;
//        $this->city = $address->city;
//        $this->area = $address->area;
//        $this->address = $address->address;
//        $this->tel = $address->tel;
//        $this->delivery_id = $delivery_id;
//        $this->delivery_name = self::$delivery_style[$delivery_id]['name'];
//        $this->delivery_price = self::$delivery_style[$delivery_id]['price'];
//        $this->payment_id = $payment_id;
//        $this->payment_name = self::$payment_style[$payment_id]['name'];
//        $this->total = $price;
//        $this->save();
//    }

    //订单和商品一对多
    public function getGoods()
    {
        return $this->hasMany(OrderGoods::className(), ['order_id' => 'id']);
    }
}
