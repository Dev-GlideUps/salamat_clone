<?php

// use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Update');
?>

<div class="branch-update">
    <?= $this->render('_form', [
        'model' => $model,
        'branchWorkingHours' => $branchWorkingHours,
        'workingHours' => $workingHours,
    ]) ?>
</div>
