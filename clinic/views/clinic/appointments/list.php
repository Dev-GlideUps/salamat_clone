<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\GridView;
use clinic\models\Appointment as Appointment;
use common\models\Country;

$country = new Country();

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\AppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Appointments');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?>
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
            <?php if (empty($dataProvider->models)) { ?>
            <div class="text-center">
                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                    <h5 class="text-hint my-3"><?= Yii::t('general', 'No results found') ?></h5>
                </div>
            </div>
            <?php } else { ?>
            <div class="card raised-card">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    // ['class' => 'common\grid\SerialColumn'],

                    // 'id',
                    [
                        'attribute' => 'patient_id',
                        'label' => Yii::t('general', 'Name'),
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
                    'patient.phone',
                    [
                        'attribute' => 'date',
                        'format' => ['date', 'long'],
                    ],
                    [
                        'attribute' => 'time',
                        'value' => function ($model) {
                            return "$model->time - $model->end_time";
                        },
                    ],
                    // 'updated_at:datetime',
                    'created_at:datetime',

                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{status}\n{view}\n{update}",
                        'buttons' => [
                            'status' => function ($url, $model, $key) {
                                return '<div class="appointment-status appointment-status-'.$model->status.' p-0 rounded-circle d-inline-block" style="width: 1.25rem; height: 1.25rem; margin: 0.125rem;" data-toggle="tooltip" data-placement="bottom" title="'.$model::statusList()[$model->status].'"></div>';
                            },
                        ],
                        'visibleButtons' => [
                            'update'=> function($model) {
                                return $model->status == Appointment::STATUS_PENDING;
                            },
                        ]
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
    </div>
</div>

<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('clinic', 'New appointment'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-new-appointment',
        ],
    ]) ?>
</div>

<div class="modal fade" id="add-new-appointment" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['create'], 'get', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Enter patient CPR') ?></div>
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