<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_072355_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name' => $this->string('20')->notNull()->unique()->comment('商品名称'),
            'sn' => $this->string('20')->notNull()->comment('货号'),
            'logo' => $this->string('255')->notNull()->comment('LOGO图片'),
            'goods_category_id' => $this->integer()->comment('商品分类id'),
            'brand_id' => $this->integer()->comment('品牌分类'),
            'market_price' => $this->decimal(10,2)->defaultValue(0)->comment('市场价格'),
            'shop_price' => $this->decimal(10,2)->defaultValue(0)->comment('商品价格'),
            'stock' => $this->integer()->defaultValue(0)->comment('库存'),
            'is_on_sale' => $this->integer(1)->defaultValue(1)->comment('是否在售'),
            'status' => $this->integer(1)->defaultValue(1)->comment('状态'),
            'sort' => $this->integer()->defaultValue(1)->comment('排序'),
            'create_time' => $this->integer()->notNull()->comment('添加时间'),
        ]);
        $this->createIndex(
            'idx-goods_category_id',
            'goods',
            'goods_category_id'
        );
        $this->createIndex(
            'idx-goods_brand_id',
            'goods',
            'brand_id'
        );
        $this->addForeignKey(
            'fk-goods_category_id',
            'goods',
            'goods_category_id',
            'goods_category',
            'id'
        );
        $this->addForeignKey(
            'fk-goods_brand_id',
            'goods',
            'brand_id',
            'brand',
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-goods_brand_id',
            'goods'
        );
        $this->dropForeignKey(
            'fk-goods_category_id',
            'goods'
        );
        $this->dropIndex(
            'idx-goods_brand_id',
            'goods'
        );
        $this->dropIndex(
            'idx-goods_category_id',
            'goods'
        );
        $this->dropTable('goods');
    }
}
