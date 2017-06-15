<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170612_072407_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->integer()->comment('商品id'),
            'content' => $this->text()->comment('商品描述'),
        ]);
        $this->createIndex(
            'idx-goods_intro-goods_id',
            'goods_intro',
            'goods_id'
        );
        $this->addForeignKey(
            'fk-goods_intro-goods_id',
            'goods_intro',
            'goods_id',
            'goods',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-goods_intro-goods_id',
            'goods_intro'
        );
        $this->dropIndex(
            'idx-goods_intro-goods_id',
            'goods_intro'
        );
        $this->dropTable('goods_intro');
    }
}
