<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_021348_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->comment('用户id'),
            'name' => $this->string(50)->notNull()->comment('收货人'),
            'tel' => $this->char(11)->notNull()->comment('手机号'),
            'province'=> $this->integer()->notNull()->comment('省份'),
            'city'=> $this->integer()->notNull()->comment('城市'),
            'area'=> $this->integer()->notNull()->comment('区县'),
            'address' => $this->string(255)->notNull()->comment('详细地址'),
            'is_default' => $this->integer(1)->defaultValue(0)->comment('是否默认地址'),
            'area' => $this->string(255)->notNull()->comment('所在地区'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
