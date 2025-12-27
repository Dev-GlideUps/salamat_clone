<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Invoice */

$this->title = Yii::t('finance', 'New invoice');
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('finance', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="invoice-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'appointment' => $appointment,
                    'services' => $services,
                    'insuranceCompanies' => $insuranceCompanies,
                    'items' => $items,
                ]) ?>
            </div>
        </div>
    </div>
</div>
