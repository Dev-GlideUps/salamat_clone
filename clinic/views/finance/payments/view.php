<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoicePayment */

$this->title = $model->transactionID;
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('finance', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
?>
<div class="container-custom invoice-payment-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/credit_card.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Yii::t('finance', 'Payment')." - {$this->title}" ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                    <h6 class="salamat-color"><?= $model->invoice->getAttributeLabel('balance') ?>: <?= $formatter->asDecimal($model->invoice->balance, 3) ?></h6>
                    <?= Html::a(Yii::t('finance', 'Show invoice'), ['/finance/invoices/view', 'id' => $model->invoice_id], ['class' => 'mdt-subtitle-2']) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'invoice_id',
                                'value' => function ($model) {
                                    return $model->invoice->invoiceID;
                                },
                            ],
                            [
                                'attribute' => 'id',
                                'label' => Yii::t('finance', 'Transaction ID'),
                                'value' => function ($model) {
                                    return $model->transactionID;
                                },
                            ],
                            [
                                'attribute' => 'payment_method',
                                'value' => function ($model) {
                                    return $model::methodList()[$model->payment_method];
                                },
                            ],
                            [
                                'attribute' => 'amount_paid',
                                'format' => ['decimal', 3],
                            ],
                            'created_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
