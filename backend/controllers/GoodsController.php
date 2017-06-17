<?php

namespace backend\controllers;


use backend\filters\AdminFilter;
use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{
    //增
    public function actionAdd()
    {
        $model = new Goods();
        $intro = new GoodsIntro();
        //判断是不是post
        if ($model->load(\Yii::$app->request->post()) && $intro->load(\Yii::$app->request->post())) {
            $day = date('Y-m-d');
            $day_count = GoodsDayCount::findOne(['day' => $day]);
            if ($day_count) {
                //获取该记录的count值 +1
                $day_count->count++;
            } else {
                //创建一条记录
                $day_count = new GoodsDayCount();
                $day_count->count = 1;
            }
            $day_count->day = $day;
            //保存当日数量
            $day_count->save();
            //得到当日数量
            $sn = $day_count->count;
            //得到日期字符串
            //算出需要的sn字段
            $model->sn = date('Ymd') . sprintf("%05d", $sn);
            //验证数据
            if ($model->validate() && $intro->validate()) {
                //保存商品
                $model->save(false);
                //获取id给下级表
                $intro->goods_id = $model->id;
                //保存商品详情
                $intro->save(false);
                //提示成功
                \Yii::$app->session->setFlash('success', '添加商品： ' . $model->name . ' 成功!');
            } else {
                var_dump($model->getErrors());
                exit();
            }
            return $this->redirect(['goods/index']);
        } else {
            //获取所有分类选项
            $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
            return $this->render('add', ['model' => $model, 'intro' => $intro, 'categories' => $categories, 'title' => '添加商品']);
        }
    }

//删
    public function actionDel($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $model->status = 0;
        if ($model->save()) {
            \Yii::$app->session->setFlash('success', '删除商品 ' . $model->name . '成功!');
        } else {
            \Yii::$app->session->setFlash('warning', '删除商品失败！');
        }
        return $this->redirect(['goods/index']);
    }

//改
    public function actionUpdate($id)
    {
        $model = Goods::findOne(['id' => $id]);
        $intro = GoodsIntro::findOne(['goods_id' => $id]);
        //判断是不是post
        if ($model->load(\Yii::$app->request->post()) && $intro->load(\Yii::$app->request->post())) {
            //验证数据
            if ($model->validate() && $intro->validate()) {
                //保存商品
                $model->save(false);
                //获取id给下级表
                $intro->goods_id = $model->id;
                //保存商品详情
                $intro->save(false);
                //提示成功
                \Yii::$app->session->setFlash('success', '修改商品： ' . $model->name . ' 成功!');
            } else {
                var_dump($model->getErrors());
                exit();
            }
            return $this->redirect(['goods/index']);
        } else {
            //获取所有分类选项
            $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], GoodsCategory::find()->asArray()->all());
            return $this->render('add', ['model' => $model, 'intro' => $intro, 'categories' => $categories, 'title' => '编辑商品']);
        }
    }

//查
    public function actionIndex()
    {
        $search = new GoodsSearchForm();
        $query = Goods::find()->where(['status' => 1]);
        $search->search($query);
        $page = new Pagination([
            'pageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        $models = $query->limit($page->limit)
            ->offset($page->offset)
            ->orderBy('id,sort')
            ->all();
        return $this->render('index', ['models' => $models, 'page' => $page, 'search' => $search, 'title' => '商品列表']);
    }

    //商品相册
    public function actionAlbum($id)
    {
        $goods = Goods::findOne(['id' => $id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        $title = '编辑相册 of '.$goods->name;
        return $this->render('album', ['goods' => $goods, 'title'=>$title]);
    }

    //ajax删除图片
    public function actionDelAlbum()
    {
        $id = \Yii::$app->request->post('id');
        $model = GoodsAlbum::findOne(['id' => $id]);
        if ($model && $model->delete()) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    //配置logo
    public function actions()
    {
        return [
            //图片上传
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/goods',
                'baseUrl' => '@web/upload/goods',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
//                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png', 'jpeg'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {
                },
                'beforeSave' => function (UploadAction $action) {
                },
                'afterSave' => function (UploadAction $action) {
                    $model = new GoodsAlbum();
                    if ($model->goods_id = \Yii::$app->request->post('goods_id')) {
                        $model->img_path = $action->getWebUrl();
                        $model->save();
                    }
                    $action->output['fileUrl'] = $action->getWebUrl();
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];

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