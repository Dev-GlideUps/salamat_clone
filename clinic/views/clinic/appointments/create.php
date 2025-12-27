<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Appointment */

$this->title = Yii::t('clinic', 'New appointment');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Appointments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="appointment-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'patient' => $patient,
                    'clinicPatient' => $clinicPatient,
                ]) ?>
            </div>
        </div>
    </div>
</div>
