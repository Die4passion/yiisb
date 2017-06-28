<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 * @property string $re_password
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    //数据表没有的字段
    public $password;
    public $captcha;
    public $re_password;
    public $agree;
    public $sms_captcha;

    const SCENARIO_REGISTER = 'register';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'tel', 'email'], 'required'],
            [['re_password', 'password', 'sms_captcha'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['password'], 'string', 'length' => [6, 12], 'tooShort' => '密码长度不够', 'tooLong' => '密码不能太长啦', 'on' => self::SCENARIO_REGISTER],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            [['username'], 'unique', 'message' => '该账号已注册，请直接登录！', 'on' => self::SCENARIO_REGISTER],
            [['tel'], 'unique', 'message' => '该手机号已被注册！', 'on' => self::SCENARIO_REGISTER],
            [['email'], 'unique', 'message' => '该邮箱已被注册！', 'on' => self::SCENARIO_REGISTER],
            [['captcha'], 'captcha'],
            [['re_password'], 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致!', 'on' => self::SCENARIO_REGISTER],
            [['agree'], 'required', 'requiredValue' => true, 'message' => '你必须遵守MIT协议！', 'on' => self::SCENARIO_REGISTER],
            //短信验证规则
            [['sms_captcha'], 'validateSms', 'on' => self::SCENARIO_REGISTER]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '认证码',
            'password_hash' => '密码密文',
            'email' => '邮箱',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'password' => '密码',
            'captcha' => '验证码',
            're_password' => '确认密码',
            'agree' => '我已年满18岁并遵守《MIT licence》',
            'sms_captcha' => '短信验证码'
        ];
    }

//    public function behaviors()
//    {
//        return [[
//            'class' => TimestampBehavior::className(),
//            'attributes' => [
//                'createdAtAttribute' => 'create_time',
//                'updatedAtAttribute' => 'update_time',
//            ]
//        ]];
//    }

    //短信验证规则
    public function validateSms()
    {
        //缓存里面没有该电话号码
        $value = Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value || $this->sms_captcha != $value){
            $this->addError('sms_captcha','验证码不正确');
        }
    }
    //保存之前执行
    public function beforeSave($insert)
    {
        if ($insert) {
            //创建时间
            $this->created_at = time();
            //加入状态
            $this->status = 1;
            //     ↓↓   下面表示更新数据，但是要排除更新的是最后登录时间和最后登录ip，这样就能完美记录用户修改的时间了。
        } elseif ($this->last_login_time == $this->getOldAttribute('last_login_time') && $this->last_login_ip == $this->getOldAttribute('last_login_ip')) {
            //修改时间
            $this->updated_at = time();
        }
        if ($this->password) {
            //密码加密
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            //随机生成认证码
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }
        return parent::beforeSave($insert);
    }


    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }
}
