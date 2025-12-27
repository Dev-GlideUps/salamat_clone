<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\InvoicePaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('finance', 'Payments');
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/credit_card.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
        
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="row">
        <div class="col">
            <?php if (count($dataProvider->models) == 0) { ?>
                <div class="text-center">
                    <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                        <h5 class="text-hint my-3"><?= Yii::t('general', 'No results found') ?></h5>
                    </div>
                </div>
            <?php } else { ?>
            <div class="card raised-card invoice-payment-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'invoice_id',
                        'value' => function ($model) {
                            return Html::a($model->invoice->invoiceID, ['/finance/invoices/view', 'id' => $model->invoice_id]);
                        },
                        'format' => 'html',
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
                    //'updated_at',

                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{view}",
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
