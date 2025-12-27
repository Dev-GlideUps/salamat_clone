<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use patient\assets\AppAsset;
use common\widgets\Snackbar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="ltr">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="mdc-top-app-bar-visible">
<?php $this->beginBody() ?>

<div id="mdc-top-app-bar" class="bg-salamat-secondary">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto" style="height: 2.5rem;">
                <a href="<?= Yii::$app->homeUrl ?>" class="logo">
                    <?= Html::img(Yii::getAlias('@web/img/logo.png'), ['class' => 'salamat-logo']) ?>
                </a>
            </div>

            <div class="col-auto">
            </div>
        </div>
    </div>
</div>

<div id="mdc-page-content" class="overlay-scroller wide-scroller">
    <?= $content ?>
</div>

<?= Snackbar::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
