<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\PatientExamNotes */

$this->title = Yii::t('patient', 'New examination notes');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Examination notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/clipboard.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="exam-notes-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'patient' => $patient,
                    'branches' => $branches,
                ]) ?>
            </div>
        </div>
    </div>
</div>
