<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use clinic\assets\AppAsset;
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
<body class="m-0 p-0">
<?php $this->beginBody() ?>

<?= $content ?>
<?php //Snackbar::widget(); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
