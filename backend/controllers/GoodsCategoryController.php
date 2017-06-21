<?php

namespace backend\controllers;


use backend\filters\AdminFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends Controller
{
    //增
    public function actionAdd()
    {
        $model = new GoodsCategory();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id) {
                //非一级分类
                $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                $model->appendTo($parent);
            } else {
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success', '添加分类-->' . $model->name . '成功!');
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类选项
        $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
        return $this->render('add', ['model' => $model, 'categories' => $categories, 'title'=>'添加商品分类']);
    }

    //删
    public function actionDel($id)
    {
        $model = GoodsCategory::findOne(['id' => $id]);
        if (!$model->isLeaf()) {
            \Yii::$app->session->setFlash('warning', '请不要删除有子分类的商品分类');
        } elseif (Goods::findOne(['goods_category_id' => $id])) {
            \Yii::$app->session->setFlash('warning', '请不要删除有商品的商品分类');
        }else {
            $model->delete();
            \Yii::$app->session->setFlash('success', '删除商品分类-->' . $model->name . '成功！');
        }
        return $this->redirect(['goods-category/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = GoodsCategory::findOne(['id' => $id]);
        if ($model == null) {
            throw new NotFoundHttpException('分类不存在');
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id) {
                //非一级分类
                $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                $model->appendTo($parent);
            } else {
                //一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success', '添加分类-->' . $model->name . '成功!');
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类选项
        $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
        return $this->render('add', ['model' => $model, 'categories' => $categories, 'title' => '编辑商品分类']);
    }

    //查
    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();
//        $page = new Pagination([
//            'defaultPageSize' => 5,
//            'totalCount' => $query->count(),
//        ]);
//        $models = $query->orderBy('id')
//            ->offset($page->offset)
//            ->limit($page->limit)
//            ->all();

        return $this->render('index', ['models' => $models,'title'=>'商品分类列表']);
    }

    //test
    public function actionTest()
    {
        //创建一级菜单
//        $goods = new GoodsCategory();
//        $goods->name = '家用电器';
//        $goods->parent_id = 0;
//        $goods->tree = 0;
//        $goods->makeRoot();//将当前分类设置为一级分类
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $ddd = new GoodsCategory();
//        $ddd->name = '智能家电';
//        $ddd->parent_id = $parent->id;
////        $ddd->prependTo($parent);
//        $roots =GoodsCategory::find()->leaves()->all();
//        var_dump($roots);
        $categories = GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('add', ['categories' => $categories]);
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