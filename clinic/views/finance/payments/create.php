<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoicePayment */

$this->title = Yii::t('finance', 'Invoice Payment');
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('finance', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/credit_card.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="invoice-payment-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'invoice' => $model->invoice,
                ]) ?>
            </div>
        </div>
    </div>
</div>
