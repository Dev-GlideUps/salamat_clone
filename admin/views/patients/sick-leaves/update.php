<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\SickLeave */

$this->title = Yii::t('patient', 'Update Sick Leave: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Sick Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('patient', 'Update');
?>
<div class="sick-leave-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
