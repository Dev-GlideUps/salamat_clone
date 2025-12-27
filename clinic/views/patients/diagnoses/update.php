<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */

$this->title = Yii::t('patient', 'Diagnosis');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Diagnoses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $patient->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('general', 'Update');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="diagnosis-update">
                <?= $this->render('_form', [
                    'model' => $model,
                    'patient' => $patient,
                    'branches' => $branches,
                    'favDiagnoses' => $favDiagnoses,
                ]) ?>
            </div>
        </div>
    </div>
</div>
