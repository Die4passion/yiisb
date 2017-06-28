<?php

namespace frontend\controllers;


use backend\models\GoodsCategory;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\User;

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
            $name = \Yii::$app->request->get('name');
            //保存数据，有验证码一定要false
            $model->save(false);
            //自动登陆
            \Yii::$app->user->login($model, 3600 * 24);
            if ($name) {
                return $this->redirect($name);
            }
            //回到登陆之前的页面
            return $this->goHome();
        }
        //注册页面
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
                $name = \Yii::$app->request->get('name');
                //跳到上一个页面
                if ($name){
                    return $this->redirect($name);
                }
                return $this->goHome();
                //下面的效果一样
//                \Yii::$app->user->setReturnUrl($name);
//                return $this->goBack();
            } else {
                //跳到登陆页面
                return $this->render('login', ['model' => $model]);
            }
        } else {
            //回到首页
            return $this->goHome();
        }
    }

    //修改用户信息
    public function actionUpdate()
    {
        //找到登陆用户id
        $id = \Yii::$app->user->identity->getId();
        //实例化
        $model = Member::findOne(['id' => $id]);
        //接收数据和验证
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存数据，有验证码一定要false
            $model->save(false);
            //回到修改之前的页面
            return $this->goHome();
        }
        //修改页面
        return $this->render('register', ['model' => $model]);
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        //页面不跳转
        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    //调试方法
    public function actionTest()
    {
        $captcha = rand(100000, 999999);
        $result = \Yii::$app->smsCaptcha->setNum(15308362170)->setParam(['captcha' => $captcha, 'name' => '李勋'])->send();
        if ($result) {
            echo $captcha . '发送成功';
        } else {
            echo '发送失败';
        }
    }

    //发送短信
    public function actionSendSms()
    {
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[35678]\d{9}$/', $tel)) {
            return '手机号不正确哦~';
        }
        $captcha = rand(1000, 9999);
        $result = \Yii::$app->smsCaptcha->setNum($tel)->setParam(['captcha' => $captcha, 'name' => $tel])->send();
        if ($result) {
            \Yii::$app->cache->set('tel_' . $tel, $captcha, 5 * 60);
            return 'success';
        } else {
            return '发送失败';
        }
    }

    //用户权限控制
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['register', 'login', 'update', 'logout'],
//                'rules' => [
//                    [
//                        'actions' => ['register', 'login'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'actions' => ['logout', 'update'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//        ];
//    }
//    //控制器中间层
//    public function beforeAction($action)
//    {
//        //如果未登陆直接返回
//        if (\Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//        //获取路径
//        return parent::beforeAction($action);
//    }
}