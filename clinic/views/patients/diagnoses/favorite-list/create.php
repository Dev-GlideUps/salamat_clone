<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */

$this->title = Yii::t('general', 'New favorite');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Diagnoses'), 'url' => ['/patients/diagnoses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Favorite'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/heart.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="diagnosis-create">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
