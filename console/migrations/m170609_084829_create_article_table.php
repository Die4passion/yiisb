<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170609_084829_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'intro' => $this->text()->comment('简介'),
            'article_category_id' => $this->integer()->notNull()->comment('文章分类id'),
            'sort' => $this->integer(11)->defaultValue(1)->comment('排序'),
            'status' => $this->integer(2)->defaultValue(1)->comment('状态'),
            'create_time' => $this->integer(11)->comment('创建时间'),
        ]);
        //创建索引
        $this->createIndex(
            'idx-article_category_id',
            'article',
            'article_category_id'
        );
        //添加外键
        $this->addForeignKey(
            'fk-article_category_id',
            'article',
            'article_category_id',
            'article_category',
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-article_category_id',
            'article'
        );
        $this->dropIndex(
            'idx-article_category_id',
            'article'
        );
        $this->dropTable('article');
    }
}
