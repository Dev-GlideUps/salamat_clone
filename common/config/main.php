<?php
return [
    // Application name
    'name' => 'SALAMAT',
    'timeZone' => 'Asia/Bahrain', 
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 'assetManager' => [
        //     'bundles' => [
        //         'dosamigos\google\maps\MapAsset' => [
        //             'options' => [
        //                 'key' => 'AIzaSyD0bH89DtxSKlsW4wyDDxBSZp20XM8gjVA',
        //                 'language' => 'en',
        //                 'version' => 'weekly',
        //             ],
        //         ],
        //     ],
        // ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
