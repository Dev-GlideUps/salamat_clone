<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Speciality */

$this->title = Yii::t('clinic', 'New speciality');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Specialities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('general', 'Create');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="card raised-card speciality-create">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
