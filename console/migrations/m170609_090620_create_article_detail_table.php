<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170609_090620_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->integer()->notNull()->comment('文章id'),
            'content'=> $this->text()->comment('简介'),
        ]);
        $this->createIndex(
            'idx-article_detail_article_id',
            'article_detail',
            'article_id'
        );
        $this->addForeignKey(
            'fk-article_detail_article_id',
            'article_detail',
            'article_id',
            'article',
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-article_detail_article_id',
            'article_detail'
        );
        $this->dropIndex(
            'idx-article_detail_article_id',
            'article_detail'
        );
        $this->dropTable('article_detail');
    }
}
