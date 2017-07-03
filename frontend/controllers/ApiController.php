<?php

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\ChangePasswordForm;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    //关闭跨站攻击验证
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

    //用户注册
    public function actionUserRegister()
    {
        $request = \Yii::$app->request;
        //var_dump($request->post('username'));exit();
        if ($request->isPost) {
            $member = new Member();
            $member->username = $request->post('username');
            $member->password = $request->post('password');
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            if ($member->validate()) {
                $member->save();
                return ['result' => $member->toArray()];
            }
            return ['errorCode' => 1001, 'errorMsg' => $member->getErrors()];
        }
        return ['errorCode' => 1011, 'errorMsg' => '请使用post请求'];
    }

    //用户登陆
    public function actionUserLogin()
    {
        if (\Yii::$app->user->isGuest) {
            $request = \Yii::$app->request;
            if ($request->isPost) {
                $model = new LoginForm();
                $model->username = $request->post('username');
                $model->password = $request->post('password');
                if ($model->validate()) {
                    return ['result' => '用户'.$model->username.'登陆成功'];
                }
                return ['errorCode' => 1001, 'errorMsg' => $model->getErrors()];
            }
            return ['errorCode' => 1011, 'errorMsg' => '请使用post请求'];
        }
        return ['errorCode' => 1011, 'errorMsg' => '你已经登陆了'];
    }

    //用户注销
    public function actionUserLogout()
    {
        self::isLogin();
        \Yii::$app->user->logout();
        return ['result' => '注销成功'];
    }

    //修改密码
    public function actionUserUpdatePassword()
    {
        self::isLogin();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model = new ChangePasswordForm();
            //$model->username = $request->post('username');
            $model->old_password = $request->post('old_password');
            $model->new_password = $request->post('new_password');
            $model->re_password = $request->post('re_password');
            if ($model->validate()) {
                $member = Member::findOne(['id' => \Yii::$app->user->getId()]);
                $member->password = $model->new_password;
                $member->save();
                return ['result' => $member->password];
            }
            return ['errorCode' => 1001, 'errorMsg' => $model->getErrors()];
        }
        return ['errorCode' => 1011, 'errorMsg' => '请使用post请求'];
    }

    //当前登录用户的信息
    public function actionUserInfo()
    {
        self::isLogin();
        return ['result' => $member->toArray()];
    }

    //添加收货地址
    public function actionAddressAdd()
    {
        self::isLogin();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $address = new Address();
            $address->name = $request->post('name');
            $address->tel = $request->post('tel');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->area = $request->post('area');
            $address->address = $request->post('address');
            $address->is_default = $request->post('is_default') ? $request->post('is_default') : 0;
            if ($address->is_default){
                self::cancelDefault();
            }
            if ($address->validate()) {
                $address->save();
                return ['result' => $address->toArray()];
            }
            return ['errorCode' => 1001, 'errorMsg' => $address->getErrors()];
        }
        return ['errorCode' => 1011, 'errorMsg' => '请使用post请求'];
    }

    //修改收货地址
    public function actionAddressUpdate()
    {
        self::isLogin();
        $request = \Yii::$app->request;
        if ($request->isGet) {
            $address_id = $request->get('address_id');
            $model = Address::findOne(['id' => $address_id]);
            return ['result' => $model->toArray()];
        }
        if ($request->isPost) {
            $address_id = $request->post('address_id');
            if (!$address_id) {
                return ['errorCode' => 1001, 'errorMsg' => '必须传入地址id'];
            }
            $address = Address::findOne(['id' => $address_id]);
            if (!$address) {
                return ['errorCode' => 1001, 'errorMsg' => '请输入已经存在的地址id'];
            }
            $address->name = $request->post('name');
            $address->tel = $request->post('tel');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->area = $request->post('area');
            $address->address = $request->post('address');
            $address->is_default = $request->post('is_default') ? $request->post('is_default') : 0;
            if ($address->is_default){
                self::cancelDefault();
            }
            if ($address->validate()) {
                $address->save();
                return ['result' => $address->toArray()];
            }
            return ['errorCode' => 1001, 'errorMsg' => $address->getErrors()];
        }
        return ['errorCode' => 1011, 'errorMsg' => '请使用post或get请求'];
    }

    //删除收货地址
    public function actionAddressDel()
    {
        self::isLogin();
        $request = \Yii::$app->request;
        if ($request->isGet) {
            $address_id = $request->get('address_id');
            if (!$address_id) {
                return ['errorCode' => 1001, 'errorMsg' => '必须传入地址id'];
            }
            $address = Address::findOne(['id' => $address_id]);
            if (!$address) {
                return ['errorCode' => 1001, 'errorMsg' => '请输入已经存在的地址id'];
            }
            $address->delete();
            return ['result' => '收货地址'.$address->address.'删除成功'];
        }
        return ['errorCode' => 1011, 'errorMsg' => '请使用get请求'];
    }

    //收货地址全部
    public function actionAddressList()
    {
        self::isLogin();
        $addresses = Address::find()->where(['member_id' => \Yii::$app->user->identity->getId()])->asArray()->all();
        return ['result' => $addresses];
    }

    //取消全部默认
    private function cancelDefault()
    {
        $member_id = \Yii::$app->user->identity->getId();
        //取消全部默认
        $addresses = Address::findAll(['member_id' => $member_id]);
        foreach ($addresses as $address) {
            if ($address->is_default) {
                $address->is_default = 0;
                $address->save();
            }
        }
    }


    //测试方法
    public function actionTest()
    {

    }

    //判断用户是否登陆
    private function isLogin()
    {
        if (\Yii::$app->user->isGuest) {
            return ['errorCode' => 1011, 'errorMsg' => '请先登陆'];
        }
        $member = Member::findOne(['id'=>\Yii::$app->user->getId()]);
        if ($member == null) {
            return ['errorCode' => 1001, 'errorMsg' => '当前用户不存在'];
        }
    }
}