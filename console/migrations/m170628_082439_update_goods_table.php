<?php

use yii\db\Migration;

class m170628_082439_update_goods_table extends Migration
{
    public function up()
    {
        $this->alterColumn(
            'goods',
            'name',
            'string not null'
        );
    }

    public function down()
    {
        echo "m170628_082439_update_goods_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
