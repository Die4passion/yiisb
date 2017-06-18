<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $label
 * @property string $url
 * @property string $description
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['label', 'sort'], 'required'],
            [['description'], 'string'],
            [['label'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
            [['parent_id'], 'compare', 'operator' => '!=', 'compareAttribute' => 'id', 'message' => '请不要设置自己当自己的儿子'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上级菜单',
            'name' => '标签名',
            'url' => '地址/路由',
            'description' => '描述',
            'sort' => '排序',
        ];
    }

    //获取全部下级菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->orderBy('sort');
    }

    //获取当前上级菜单
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    //获取全部顶级菜单
    public static function getParents()
    {
        return [0 => '顶级分类'] + ArrayHelper::map(self::find()->where(['parent_id' => 0])->all(), 'id', 'label');
    }
    //保存前执行的代码
//    public function beforeSave($insert)
//    {
//        if ($insert && !$this->parent_id) {
//            $this->parent_id = 0;
//        }
//        return parent::beforeSave($insert);
//    }
}
