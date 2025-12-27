<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-clinic',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'root/index',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'clinic\controllers',
    // 'modules' => [
    //     'admin' => [
    //         'class' => 'mdm\admin\Module',
    //     ],
    // ],
    'components' => [
        'authManager' => [
            'class' => 'clinic\components\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-clinic',
        ],
        'user' => [
            'class' => 'clinic\models\WebUser',
            'identityClass' => 'clinic\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['/user/sign-in'],
            'identityCookie' => ['name' => '_identity-clinic', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the clinic
            'name' => 'clinic-app',
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
            'errorAction' => 'root/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@clinic/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/clinic/<controller:(\w|-)+>/<action:(\w|-)+>/<id:\d+>' =>    '/clinic/<controller>/<action>',
                'GET /clinic/<controller:doctors>/<action:photo>/<file:.*>' => '/clinic/doctors/photo',
                'GET /clinic/<controller:doctors>/<action:thumbnail>/<file:.*>' => '/clinic/doctors/thumbnail',
				'GET /clinic/<controller:patients>/<action:photo>/<file:.*>' => '/clinic/patients/photo',
                'GET /clinic/<controller:patients>/<action:thumbnail>/<file:.*>' => '/clinic/patients/thumbnail',
                '/patients/<controller:(\w|-)+>/<action:(\w|-)+>/<id:\d+>' => '/patients/<controller>/<action>',
                '/finance/<controller:(\w|-)+>/<action:(\w|-)+>/<id:\d+>' => '/finance/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
