<?php

namespace backend\controllers;


use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;

class MenuController extends Controller
{

    //查
    public function actionIndex()
    {
        $query = Menu::find();
        $page = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $models = $query->orderBy('id,sort')
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'page' => $page, 'title' => '菜单列表']);
    }

    //增
    public function actionAdd()
    {
        $model = new Menu();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '添加菜单-->' . $model->label . ' 成功');
            return $this->redirect(['menu/index']);
        } else {
            return $this->render('add', ['model' => $model, 'title' => '添加菜单']);
        }
    }

    //删
    public function actionDel($id)
    {
        $model = Menu::findOne(['id' => $id]);
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除菜单-->' . $model->label . '成功');
        return $this->redirect(['menu/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = Menu::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '编辑菜单-->' . $model->label . '成功');
            return $this->redirect(['menu/index']);
        } else {
            return $this->render('add', ['model' => $model, 'title' => '编辑菜单']);
        }
    }
}