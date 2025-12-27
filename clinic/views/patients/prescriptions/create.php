<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Prescription */

$this->title = Yii::t('patient', 'New prescription');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Prescriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/two_bottles.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="prescription-create">
                <?= $this->render('_form', [
                    'branches' => $branches,
                    'diagnosis' => $diagnosis,
                    'medicines' => $medicines,
                    'medModel' => $medModel,
                    'model' => $model,
                    'items' => $items,
                ]) ?>
            </div>
        </div>
    </div>
</div>
