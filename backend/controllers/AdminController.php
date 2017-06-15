<?php

namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\ResetPasswordForm;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    //增
    public function actionAdd()
    {
        $model = new Admin();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', '新增管理-->' . $model->username . '成功');
            } else {
                \Yii::$app->session->setFlash('warning', '添加失败！');
            }
            return $this->redirect(['admin/index']);
        }
        return $this->render('add', ['model' => $model, 'title' => '新增管理']);
    }

    //删
    public function actionDel($id)
    {
        $model = Admin::findOne(['id' => $id]);
        $model->status = 0;
        if ($model->save()) {
            \Yii::$app->session->setFlash('success', '删除狗管理 ' . $model->username . '成功!');
        } else {
            \Yii::$app->session->setFlash('warning', '删除管理员失败！');
        }
        return $this->redirect(['admin/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = Admin::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', '修改管理-->' . $model->username . '成功');
                \Yii::$app->session->setTimeout(200);
            } else {
                \Yii::$app->session->setFlash('warning', '修改失败！');
            }
            return $this->redirect(['admin/index']);
        }
        return $this->render('add', ['model' => $model, 'title' => '修改管理']);
    }

    //查
    public function actionIndex()
    {
        $models = Admin::find()->where(['<>', 'status', 0])->all();
        return $this->render('index', ['models' => $models]);
    }

    //登陆
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            \Yii::$app->session->setFlash('success', $model->username . '：欢迎回到1024论坛 !');
            return $this->redirect(['admin/index']);
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    //修改密码
    public function actionResetPassword($id)
    {
        //实例化表单模型
        $model = new ResetPasswordForm();
        //查出一条数据
        $admin = Admin::findOne(['id' => $id]);
        //判断是否有post请求并赋值给￥model
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存新密码
            $admin->password = $model->new_password;
            $admin->save();
            \Yii::$app->session->setFlash('success', '修改密码成功');
            return $this->redirect(['admin/index']);
        } else {
            return $this->render('reset-password', ['admin' => $admin, 'model' => $model]);
        }
    }
    //行为
    public function behaviors()
    {
        return [
            'acf' => [
                'class' =>AccessControl::className(),
//                'only' => ['add', 'del', 'update', 'index','reset-password'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add', 'del', 'update', 'index','reset-password'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
}