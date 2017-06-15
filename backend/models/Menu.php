<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 * @property string $description
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
            [['parent_id'], 'integer'],
            [['name', 'url'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上级分类id',
            'name' => '名称',
            'url' => '路由(权限)',
            'description' => '描述',
        ];
    }

    //建立菜单间关系
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }
}
