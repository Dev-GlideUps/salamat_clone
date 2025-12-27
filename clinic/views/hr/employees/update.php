<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\hr\Employee */

$this->title = Yii::t('hr', 'Update Employee');
$this->params['breadcrumbs'][] = Yii::t('hr', 'Human resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('general', 'Update');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="employee-update">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
