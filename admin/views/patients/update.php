<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model patient\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Patients Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Patients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('patient', 'Update');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="patient-create">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
