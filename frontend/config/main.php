<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN', //语言
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => \frontend\models\Member::className(),
            'enableAutoLogin' => true,
            'authTimeout' => 3600*24*7,
            'on beforeLogin' => function($event) {
                $member = $event->identity;
                $member->last_login_time = time();
                $member->last_login_ip = ip2long(Yii::$app->request->userIP);
                $member->save(false);
            },
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'suffix' => '.html',
            'rules' => [
            ],
        ],
        //配置短信验证组件
        'smsCaptcha' => [
            'class' => \frontend\components\SmsCaptcha::className(),
            'app_key' => '24478196',
            'app_secret' => 'b9677931be9fa82727d2b0decbf28807',
            'sign_name'=>'小航的博客',
            'template_code'=>'SMS_71475156',
        ]
    ],
    'params' => $params,
    //默认路由和布局文件
    'defaultRoute' => 'index/index',
    'layout' => 'index',
];
