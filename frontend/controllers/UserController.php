<?php

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;

class UserController extends Controller
{
    //定义布局文件
    public $layout = 'login';

    public function actionRegister()
    {
        //实例化，定义场景
        $model = new Member(['scenario' => Member::SCENARIO_REGISTER]);
        //接收数据和验证
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存数据，有验证码一定要false
            $model->save(false);
            //自动登陆
            \Yii::$app->user->login($model, 3600 * 24);
//            \Yii::$app->session->setFlash('success', $model->username . '，你好！欢迎加入1024社区');
            return $this->goHome();
        }
        return $this->render('register', ['model' => $model]);
    }

    //登陆
    public function actionLogin()
    {
        if (\Yii::$app->user->isGuest) {
            //实例化表单模型
            $model = new LoginForm();
            //接收数据和验证
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                return $this->goHome();
            } else {
                return $this->render('login', ['model' => $model]);
            }
        } else {
            return $this->goHome();
        }
    }

    //修改用户信息
    public function actionUpdate()
    {
        //找到登陆用户id
        $id = \Yii::$app->user->identity->getId();
        //实例化
        $model = Member::findOne(['id'=>$id]);
        //接收数据和验证
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存数据，有验证码一定要false
            $model->save(false);
            //自动登陆
            //\Yii::$app->user->login($model, 3600 * 24);
//            \Yii::$app->session->setFlash('success', $model->username . '，你好！欢迎加入1024社区');
            return $this->goHome();
        }
        return $this->render('register', ['model' => $model]);
    }
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    //首页
    public function actionIndex()
    {
        $this->layout = 'index';
        return $this->render('index');
    }

    //调试方法
    public function actionTest()
    {
        var_dump(\Yii::$app->user->isGuest);
    }
}