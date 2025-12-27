<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-pharmacy',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'root/index',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'pharmacy\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-pharmacy',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['/root/sign-in'],
            'identityCookie' => ['name' => '_identity-pharmacy', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the pharmacy
            'name' => 'pharmacy-app',
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
                    'basePath' => '@pharmacy/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
