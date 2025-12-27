<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use pharmacy\assets\AppAsset;
use common\widgets\Snackbar;
// use common\widgets\NavDrawer;
// use common\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="ltr">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="57x57" href="<?= Yii::getAlias('@web/app_icon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= Yii::getAlias('@web/app_icon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= Yii::getAlias('@web/app_icon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= Yii::getAlias('@web/app_icon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= Yii::getAlias('@web/app_icon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= Yii::getAlias('@web/app_icon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= Yii::getAlias('@web/app_icon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= Yii::getAlias('@web/app_icon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web/app_icon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?= Yii::getAlias('@web/app_icon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web/app_icon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= Yii::getAlias('@web/app_icon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Yii::getAlias('@web/app_icon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= Yii::getAlias('@web/app_icon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#9E9D24">
    <meta name="msapplication-TileImage" content="<?= Yii::getAlias('@web/app_icon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#9E9D24">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="mdc-top-app-bar" class="bg-salamat-secondary">
    <button class="material-icon nav-icon">menu</button>
    <?= Html::img(Yii::getAlias('@web/img/logo.png'), ['class' => 'salamat-logo']) ?>
</div>

<div id="mdc-page-content" class="nano">
    <div class="p-4"></div>
    <div class="p-5"></div>
    <?= $content ?>
    <div class="p-5"></div>
</div>

<?= Snackbar::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
