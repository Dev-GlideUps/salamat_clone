<?php

use yii\helpers\Html;
use common\grid\GridView;
use common\models\Country;
use clinic\models\Appointment;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('finance', 'Invoices');
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = $this->title;

$country = new Country();
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
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
            <div class="card raised-card invoice-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('finance', 'Invoice ID'),
                        'value' => function ($model) {
                            return $model->invoiceID;
                        },
                    ],
                    [
                        'attribute' => 'patient_id',
                        'value' => function ($model) {
                            return Html::tag('div',
                                Html::tag('div', '', ['class' => 'graphic m-0','style' => "background-image: url('{$model->patient->photoThumb}');"]).
                                Html::tag('div',
                                    $model->patient->name.
                                    Html::tag('div', $model->patient->name_alt, ['class' => 'secondary']),
                                ['class' => 'text my-0 mr-0']),
                            ['class'=> 'mdc-list-item']);
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'vat',
                        'label' => Yii::t('finance', 'VAT (10%)'),
                        'format' => ['decimal', 3],
                    ],
                    [
                        'attribute' => 'discount',
                        'format' => ['decimal', 3],
                    ],
                    [
                        'attribute' => 'total',
                        'format' => ['decimal', 3],
                    ],
                    [
                        'attribute' => 'balance',
                        'format' => ['decimal', 3],
                    ],
                    [
                        'attribute' => 'max_appointments',
                        'value' => function ($model) {
                            $completed = 0;
                            foreach ($model->appointments as $item) {
                                if ($item->status == Appointment::STATUS_COMPLETED) {
                                    $completed++;
                                }
                            }
                            return count($model->appointments) . " / {$model->max_appointments} ( {$completed} " . Appointment::statusList()[Appointment::STATUS_COMPLETED] . " )";
                        },
                    ],
                    // [
                    //     'attribute' => 'branch_id',
                    //     'value' => function ($model) {
                    //         return $model->branch->name;
                    //     },
                    // ],
                    'created_at:datetime',
                    // 'updated_at',
    
                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{pdf}\n{view}",
                        'buttons' => [
                            'pdf' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('general', 'PDF export'),
                                    'aria-label' => Yii::t('general', 'PDF export'),
                                    'target' => '_blank',
                                    'class' => 'material-icon mx-2',
                                ];
                                return Html::a("picture_as_pdf", $url, $options);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('finance', 'New invoice'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-new-invoice',
        ],
    ]) ?>
</div>

<div class="modal fade" id="add-new-invoice" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['create'], 'get', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Enter patient information') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-label-group form-group">
                    <input type="text" id="patient-cpr" class="form-control" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'CPR') ?>">
                    <label for="patient-cpr"><?= Yii::t('patient', 'CPR') ?></label>
                </div>
                <div class="form-label-group form-group">
                    <select class="form-control bootstrap-select" name="nationality" id="patient-nationality" data-live-search="true">
                        <?php foreach($country->countriesList  as $key => $value) {
                            $options = [
                                'class' => 'font-italic',
                                'value' => $key,
                            ];
                            if($key == "BH") {
                                $options['selected'] = true;
                            }
                            echo Html::tag('option', $value, $options);
                        } ?>
                    </select>
                    <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Next'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?= Html::endForm() ?>
    </div>
</div>
