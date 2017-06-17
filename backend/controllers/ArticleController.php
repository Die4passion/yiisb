<?php

namespace backend\controllers;


use backend\filters\AdminFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    //增
    public function actionAdd()
    {
        $model = new Article();
        $detail = new ArticleDetail();
        $cates = ArticleCategory::find()->where(['<>', 'status', '-1'])->all();
        if ($model->load(\Yii::$app->request->post()) && $detail->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            //根据插入的文章查找id
            $detail->article_id = $model->findone(['name' => $model->name])->id;
            $detail->save(false);
            \Yii::$app->session->setFlash('success', '添加文章-->' . $model->name . '成功!');
            return $this->redirect(['article/index']);
        } else {
            return $this->render('add', ['model' => $model,
                'cates' => $cates,
                'detail' => $detail,
                'title' => '添加文章'
            ]);
        }
    }

    //删
    public function actionDel($id)
    {
        $model = new Article();
        $model->status = -1;
        $model->save(false);
        \Yii::$app->session->setFlash('success', '删除文章-->' . $model->name . '成功!');
    }

    //改
    public function actionUpdate($id)
    {
        $model = Article::findOne(['id' => $id]);
        $detail = ArticleDetail::findOne(['article_id' => $id]);
        $cates = ArticleCategory::find()->where(['<>', 'status', '-1'])->all();
        if ($model->save(\Yii::$app->request->post()) && $model->save() && $detail->load(\Yii::$app->request->post())) {
            $model->save(false);
            $detail->article_id = $model->findone(['name' => $model->name])->id;
            $detail->save(false);
            \Yii::$app->session->setFlash('success', '修改文章-->' . $model->name . '成功!');
            return $this->redirect(['article/index']);
        } else {
            return $this->render('add', [
                'model' => $model,
                'cates' => $cates,
                'detail' => $detail,
                'title' => '编辑文章'
            ]);
        }
    }

    //查
    public function actionIndex()
    {
        $query = Article::find()->where(['<>', 'status', '-1']);
        $page = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $models = $query->orderBy('id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();
        return $this->render('index', ['page' => $page, 'models' => $models, 'title' => '文章列表']);
    }

    //查内容
    public function actionContent($id = 1)
    {
        $model = Article::findOne(['id' => $id]);
        $detail = ArticleDetail::find()->where(['article_id' => $id])->one();
        //var_dump($detail);die();
        return $this->render('content', [
            'model' => $model,
            'detail' => $detail,
            'title' => '文章内容',
        ]);
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