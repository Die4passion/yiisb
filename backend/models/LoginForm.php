<?php

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $remember;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'validateUsername'],
            [['code'], 'captcha'],
            [['remember'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'code' => '验证码',
            'remember' => '记住我',
        ];
    }


    public function validateUsername()
    {
        $admin = Admin::findOne(['username' => $this->username]);
        if ($admin) {
            if (!\Yii::$app->security->validatePassword($this->password, $admin->password_hash)) {
                $this->addError('password', '密码不正确');
            } else {
                $remember = \Yii::$app->user->authTimeout;
                \Yii::$app->user->login($admin, $remember ? $remember : '');
            }
        } else {
            $this->addError('username', '账号不存在');
        }
    }
}