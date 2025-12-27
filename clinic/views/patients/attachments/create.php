<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model patient\models\Attachment */

$this->title = Yii::t('patient', 'New attachment');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => $patient->name, 'url' => ['/clinic/patients/view', 'id' => $patient->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/attachment2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="diagnosis-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'patient' => $patient,
                    'branches' => $branches,
                    'categories' => $categories,
                ]) ?>
            </div>
        </div>
    </div>
</div>
