<?php

namespace pharmacy\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/layout.css',
        'css/style.css',
    ];
    public $js = [
        'js/script.js',
    ];
    public $depends = [
        'common\assets\BootstrapAsset',
    ];
}
