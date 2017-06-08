<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Brand::find()->where(['<>', 'status', -1]);
        $page = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $models = $query->orderBy('id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'page' => $page]);
    }

    //增
    public function actionAdd()
    {
        $model = new Brand();
        if ($model->load(\Yii::$app->request->post())) {
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->validate()) {
                if ($model->imgFile) {
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success', '品牌添加成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                die();
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //删
    public function actionDel($id)
    {
        $model = Brand::findOne(['id' => $id]);
        $model->status = -1;
        $model->save(false);
        \Yii::$app->session->setFlash('success', '删除' . $model->name . '成功');
        return $this->redirect(['brand/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = Brand::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post())) {
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->validate()) {
                if ($model->imgFile) {
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    $model->logo = $fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('success', '品牌修改成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                die();
            }
        }
        return $this->render('add', ['model' => $model]);
    }

}
