<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */

$this->title = "{$model->clinic->name} - {$model->name}";
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "{$model->clinic->name} - {$model->name}", 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('general', 'Update');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/door_open.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="card raised-card branch-update">
                <?= $this->render('_form', [
                    'model' => $model,
                    'branchWorkingHours' => $branchWorkingHours,
                    'workingHours' => $workingHours,
                ]) ?>
            </div>
        </div>
    </div>
</div>
