<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 *
 * @property Brand $brand
 * @property GoodsCategory $goodsCategory
 * @property GoodsIntro[] $goodsIntros
 */
class Goods extends \yii\db\ActiveRecord
{
    public static $saleOptions = [
        1 => '货源充足',
        0 => '没货啦'
    ];
    public static $statusOptions = [
        1 => '饱满',
        0 => '萎靡'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sn'], 'required'],
            [['goods_category_id', 'stock', 'brand_id', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn', 'logo'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['brand_id'], 'exist', 'skipOnError' => false, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['goods_category_id'], 'exist', 'skipOnError' => false, 'targetClass' => GoodsCategory::className(), 'targetAttribute' => ['goods_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    public static function getBrands()
    {
        return ArrayHelper::map(Brand::find()->where(['status' => 1])->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(), ['id' => 'goods_category_id']);
    }

    //商品和商品详情
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(), ['goods_id' => 'id']);
    }

    //商品和相册关系
    public function getAlbums()
    {
        return $this->hasMany(GoodsAlbum::className(), ['goods_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }
}
