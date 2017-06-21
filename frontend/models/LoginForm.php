<?php

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $captcha;
    public $remember;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'validateUsername'],
            [['captcha'], 'captcha'],
            [['remember'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'captcha' => '验证码',
            'remember' => '记住我',
        ];
    }


    public function validateUsername()
    {
        $member = self::getUser();
        if ($member) {
            if (!\Yii::$app->security->validatePassword($this->password, $member->password_hash)) {
                $this->addError('password', '密码不正确');
            } else {
                $remember = \Yii::$app->user->authTimeout;
                \Yii::$app->user->login($member, $remember ? $remember : '');
            }
        } else {
            $this->addError('username', '账号/邮箱/手机号 错误');
        }

    }

    private function getUser()
    {
        if (Member::findOne(['username' => $this->username])) {
            return Member::findOne(['username' => $this->username]);
        } elseif (Member::findOne(['tel' => $this->username])) {
            return Member::findOne(['tel' => $this->username]);
        } elseif (Member::findOne(['email' => $this->username])) {
            return Member::findOne(['email' => $this->username]);
        } else {
            return false;
        }
    }
}