<?php

namespace frontend\models;


/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $tel
 * @property integer $province
 * @property integer $city
 * @property string $area
 * @property string $address
 * @property integer $is_default
 *
 * @property Member $member
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'is_default'], 'integer'],
            [['name', 'tel', 'province', 'city', 'area', 'address'], 'required'],
            [['name', 'province', 'city', 'area'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
            [['area', 'address'], 'string', 'max' => 255],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
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
            'tel' => '手机号',
            'province' => '省份',
            'city' => '城市',
            'area' => '所在地区',
            'address' => '详细地址',
            'is_default' => '是否默认地址',
        ];
    }

    public function beforeSave($insert)
    {
        $this->member_id = \Yii::$app->user->getId();
        return parent::beforeSave($insert);
    }
}
