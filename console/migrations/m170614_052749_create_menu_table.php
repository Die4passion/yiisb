<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170614_052749_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(0)->comment('上级菜单'),
            'label' => $this->string(20)->notNull()->comment('名称'),
            'url' => $this->string(255)->comment('路由'),
            'description' => $this->text()->comment('描述'),
            'sort' => $this->integer()->defaultValue(1)->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
