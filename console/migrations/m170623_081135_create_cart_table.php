<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170623_081135_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer()->comment('商品ID'),
            'member_id' => $this->integer()->comment('用户ID'),
            'amount' => $this->integer()->comment('商品数量'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
