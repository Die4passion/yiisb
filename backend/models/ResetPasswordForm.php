<?php

namespace backend\models;


use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $username;
    public $old_password;
    public $new_password;
    public $re_password;
    public $code;

    public function rules()
    {
        return [
            [['username', 'old_password', 'new_password', 're_password'], 'required',],
            [['code'], 'captcha'],
            //旧密码要正确
            [['old_password'], 'validatePassword2'],
            //新密码不能等于旧密码
            [['new_password'], 'compare', 'operator' => '!=', 'compareAttribute' => 'old_password', 'message' => '新密码不能等于旧密码'],
            //修改密必须控制长度
            [['new_password'], 'string', 'length' => [6, 12], 'tooShort' => '密码长度不够', 'tooLong' => '密码不能太长啦'],
            //新密码等于确认新密码
            [['re_password'], 'compare', 'compareAttribute' => 'new_password', 'message' => '两次密码不一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'old_password' => '旧密码',
            'new_password' => '新密码',
            're_password' => '确认新密码',
            'code' => '验证码',
        ];
    }

    //验证旧密码
    public function validatePassword2()
    {
        //根据username得到该用户
        $admin = Admin::findOne(['username' => $this->username]);
        //用户存在
        if ($admin) {
            //判断旧密码是否正确
            if (!\Yii::$app->security->validatePassword($this->old_password, $admin->password_hash)) {
                //旧密码不正确
                $this->addError('old_password', '旧密码错误');
            }
        }
    }

}