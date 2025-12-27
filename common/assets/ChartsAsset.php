<?php

namespace common\assets;

use yii\web\AssetBundle;

class ChartsAsset extends AssetBundle
{
    public $sourcePath = '@common/web/chart.js';
    public $css = [
        'chart.css',
    ];
    public $js = [
        'chart.bundle.min.js',
    ];
    public $depends = [
        'common\assets\BootstrapAsset',
    ];
}
