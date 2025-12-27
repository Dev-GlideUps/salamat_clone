<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model insurance\models\Company */

$this->title = Yii::t('general', 'Create Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
