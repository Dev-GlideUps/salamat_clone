<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-insurance',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'root/index',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'insurance\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-insurance',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['/root/sign-in'],
            'identityCookie' => ['name' => '_identity-insurance', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the insurance
            'name' => 'insurance-app',
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
            'errorAction' => 'root/index',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@insurance/messages',
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
