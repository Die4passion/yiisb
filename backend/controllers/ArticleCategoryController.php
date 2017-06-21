<?php

namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    //查
    public function actionIndex()
    {
        $query = ArticleCategory::find()->where(['<>', 'status', '-1']);
        $page = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $models = $query->orderBy('id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();

        return $this->render('index', ['models' => $models, 'page' => $page, 'title' => '文章分类列表']);
    }

    //增
    public function actionAdd()
    {
        $model = new ArticleCategory();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '添加分类-->' . $model->name . ' 成功');
            return $this->redirect(['article-category/index']);
        } else {
            return $this->render('add', ['model' => $model, 'title' => '添加文章分类']);
        }
    }

    //删
    public function actionDel($id)
    {
        if (Article::findOne(['article_category_id' => $id])) {
            \Yii::$app->session->setFlash('warning', '请不要删除有文章的文章分类');
        } else {
            $model = ArticleCategory::findOne(['id' => $id]);
            $model->status = -1;
            $model->save(false);
            \Yii::$app->session->setFlash('success', '删除分类-->' . $model->name . '成功');
        }
        return $this->redirect(['article-category/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = ArticleCategory::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '修改分类-->' . $model->name . '成功');
            return $this->redirect(['article-category/index']);
        } else {
            return $this->render('add', ['model' => $model, 'title' => '修改文章分类']);
        }
    }

    //权限规则
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => AdminFilter::className(),
            ],
        ];
    }
}
