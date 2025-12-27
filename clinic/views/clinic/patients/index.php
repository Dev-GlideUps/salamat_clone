<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\GridView;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $searchModel patient\models\PatientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = $this->title;

$country = new Country();

?>

<div class="container-custom patient-index">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?>
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
            <div class="card raised-card">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => Yii::t('general', 'Name'),
                        'value' => function ($model) {
                            return Html::beginTag('a', ['class' => 'mdc-list-item', 'href' => Url::to(['view', 'id' => $model->id])]).
                                Html::tag('div', '', ['class' => 'graphic','style' => "background-image: url('{$model->photoThumb}');"]).
                                Html::beginTag('div', ['class' => 'text']).
                                    $model->name.
                                    Html::tag('div', $model->name_alt, ['class' => 'secondary']).
                                    Html::endTag('div').
                            Html::endTag('a');
                        },
                        'contentOptions' => ['class' => 'p-0'],
                        'format' => 'raw',
                    ],
                    'cpr',
                    [
                        'attribute' => 'nationality',
                        'value' => function ($model) {
                            if ($model->nationality === null) {
                                return null;
                            }
                            
                            $country = new Country();
                            return $country->countriesList[$model->nationality];
                        },
                    ],
                    'phone',
                    [
                        'attribute' => 'gender',
                        'value' => function ($model) {
                            return ($model->gender === null ? null : $model::genderList()[$model->gender]);
                        },
                    ],
                    // [
                    //     'label' => Yii::t('general', 'Age'),
                    //     'value' => function ($model) {
                    //         return $model->age;
                    //     },
                    // ],
                    'clinicPatient.profile_ref',
                    // [
                    //     'attribute' => 'height',
                    //     'value' => function ($model) {
                    //         return ($model->height === null ? null : Yii::t('general', '{height} cm', ['height' => $model->height]));
                    //     },
                    // ],
                    // [
                    //     'attribute' => 'weight',
                    //     'value' => function ($model) {
                    //         return ($model->weight === null ? null : Yii::t('general', '{weight} Kg', ['weight' => $model->weight]));
                    //     },
                    // ],
                    //'photo',
                    //'created_at',
                    //'updated_at',

                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{view}\n{update}",
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('patient', 'Add patient'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-patient',
        ],
    ]) ?>
</div>

<div class="modal fade" id="add-patient" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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