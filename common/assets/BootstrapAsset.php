<?php

namespace common\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@common/web/bootstrap';
    public $css = [
        "https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Cairo:400,600,700&display=swap&subset=arabic",
        "https://fonts.googleapis.com/icon?family=Material+Icons",
        'bootstrap.custom.css',
        'nanoscroller.css',
        'overlay-scrollbars.css',
        'lists.css',
        'progress-indicators.css',
        'material-icons.css',
        'buttons.css',
        'fab.css',
        'bootstrap-select.css',
        'bootstrap-datepicker.css',
        'modals.css',
        'snackbars.css',
        'side-sheets.css',
        'timepicker.css',
    ];
    public $js = [
        'bootstrap.bundle.js',
        'init.js',
        'nanoscroller.custom.js',
        'jquery.overlay-scrollbars.min.js',
        'lists.js',
        'bootstrap-select.min.js',
        'bootstrap-datepicker.min.js',
        'snackbars.js',
        'side-sheets.js',
        'timepicker.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
