<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170611_005428_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->comment('树id')->notNull()->defaultValue(0),
            'lft' => $this->integer()->comment('左值')->notNull(),
            'rgt' => $this->integer()->comment('右值')->notNull(),
            'depth' => $this->integer()->comment('层级')->notNull(),
            'name' => $this->string(50)->notNull()->unique()->comment('名称'),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('上级分类id'),
            'intro' => $this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
