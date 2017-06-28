<?php

namespace frontend\models;


use yii\base\Model;

class NumForm extends Model
{
    public $input;

    public function rules()
    {
        return [
            [['input'], 'required'],
            [['input'], 'integer'],
            [['input'], 'string', 'length' => [4, 4]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'input' => '猜的数字',
        ];
    }

}