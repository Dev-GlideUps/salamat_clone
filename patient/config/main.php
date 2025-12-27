<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-patient',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'root/index',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'patient\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-patient',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['/root/sign-in'],
            'identityCookie' => ['name' => '_identity-patient', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the patient
            'name' => 'patient-app',
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
                    'basePath' => '@patient/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'GET <controller:patient>/<action:photo>/<file:.*>' => '/patient/photo',
                'GET <controller:patient>/<action:thumbnail>/<file:.*>' => '/patient/thumbnail',
                '<action:(\w|\-)+>' => 'root/<action>',
                '<action:(\w|\-)+>/<id:(\w|\-|\.)+>' => 'root/<action>',
                '<controller:(\w|\-)+>/<action:(\w|\-)+>/<id:(\w|\-|\.)+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
