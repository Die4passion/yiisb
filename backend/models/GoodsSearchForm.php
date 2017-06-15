<?php

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    public function rules()
    {
        return [
            ['name', 'string', 'max' => 50],
            ['sn', 'number'],
            [['minPrice', 'maxPrice'], 'double'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '商品名称',
            'sn' => '货号',
            'minPrice' => '最低价',
            'maxPrice' => '最高价',
        ];
    }

    public function search(ActiveQuery $query)
    {
        $this->load(\Yii::$app->request->get());
        if ($this->name) {
            $query->andWhere(['like', 'name', $this->name]);
        }
        if ($this->sn) {
            $query->andWhere(['like', 'sn', $this->sn]);
        }
        if ($this->minPrice) {
            $query->andWhere(['>=', 'minPrice', $this->sn]);
        }
        if ($this->maxPrice) {
            $query->andWhere(['<=', 'maxPrice', $this->sn]);
        }
    }
}