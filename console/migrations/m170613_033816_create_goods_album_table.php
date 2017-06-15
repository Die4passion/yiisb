<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_album`.
 */
class m170613_033816_create_goods_album_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_album', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer()->comment('商品id'),
            'img_path' => $this->string(255)->comment('图片路径'),
        ]);
        $this->createIndex(
            'idx-the_goods_id',
            'goods_album',
            'goods_id'
        );
        $this->addForeignKey(
            'fk-the_goods_id',
            'goods_album',
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
            'fk-the_goods_id',
            'goods_album'
        );
        $this->dropIndex(
            'idx-the_goods_id',
            'goods_album'
        );
        $this->dropTable('goods_album');
    }
}
