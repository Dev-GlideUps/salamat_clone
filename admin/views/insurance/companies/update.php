<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model insurance\models\Company */

$this->title = Yii::t('general', 'Update Company: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('general', 'Update');
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
