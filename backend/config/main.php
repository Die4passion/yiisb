<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',//语言
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'authTimeout' => 3600*24*7,
            'on beforeLogin' => function($event) {
                $admin = $event->identity;
                $admin->last_login_time = time();
                $admin->last_login_ip = Yii::$app->request->userIP;
                $admin->save();
            },
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['admin/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'rules' => [
            ],
        ],
        'qiniu' => [
            'class' => \backend\components\Qiniu::className(),
            'accessKey' => '9tJjG0SIiggx-6IYIaIFxtuzs94-8S1_l0ToaaoB',
            'secretKey' => '-J258OqeSN8T8Yf0hlJjiOv5ByGCpy9UTi31O5WE',
            'bucket' => 'die2',
            'domain' => 'http://or9uphb1d.bkt.clouddn.com/',
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'admin/index',
];
