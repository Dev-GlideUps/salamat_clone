<?php

use yii\helpers\Html;
use common\grid\DetailView;
use common\models\WorkingHoursForm;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$services = $model->services;
$formatter = Yii::$app->formatter;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <ul class="nav nav-tabs" id="doctor-profile-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#branch-info" role="tab" aria-selected="true"><?= Yii::t('clinic', 'Branch information') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#branch-services" role="tab" aria-selected="false"><?= Yii::t('general', 'Services') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="card-body tab-pane fade active show" id="branch-info" role="tabpanel">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                // 'id',
                                'name',
                                'name_alt',
                                [
                                    'attribute' => 'phone',
                                    'value' => function ($model) {
                                        return $model->contactNumber;
                                    },
                                ],
                                [
                                    'attribute' => 'address',
                                    'value' => function ($model) {
                                        return Html::a(Html::tag('div', 'location_on', ['class' => 'material-icon icon']).$model->address, $model->locationUrl, [
                                            'class' => 'mdc-button btn-outlined salamat-color',
                                            'target' => '_blank',
                                        ]);
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'label' => Yii::t('clinic', 'Appointments schedule'),
                                    'value' => function ($model) {
                                        $start = date('g A', strtotime($model->schedule_starting));
                                        $end = date('g A', strtotime($model->schedule_ending));
                                        return "$start - $end";
                                    },
                                ],
                                [
                                    'attribute' => 'auto_closing',
                                    'value' => function ($model) {
                                        return $model::getClosingTime($model->auto_closing);
                                    },
                                ],
                                [
                                    'label' => Yii::t('general', 'Working hours'),
                                    'value' => function ($model) {
                                        $workingHoursModel = $model->workingHoursModel;
                                        if (empty($workingHoursModel)) {
                                            return null;
                                        }

                                        return WorkingHoursForm::renderWorkingHoursTable($workingHoursModel->workingHours);
                                    },
                                    'format' => 'raw',
                                ],
                                'created_at:datetime',
                                'updated_at:datetime',
                            ],
                        ]) ?>
                        <div class="mdc-button-group direction-reverse p-0">
                            <?= Html::a(Html::tag('div', 'update', ['class'=> 'icon material-icon']).Yii::t('general', 'Update information'), ['update', 'id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                        </div>
                    </div>
                    <div class="card-body tab-pane fade" id="branch-services" role="tabpanel">
                        <h5 class="card-title"><?= Html::encode($this->title) ?></h5>

                        <?php if (empty($services)) { ?>
                            <div class="py-5 text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No Services!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="mdc-datatable" style="margin: 0 -1.25rem;">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><span><?= $services[0]->getAttributeLabel('title') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('title_alt') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('duration') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('max_appointments') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('price') ?></span></th>
                                                <th class="action-column"><span></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($services as $item) { ?>
                                            <tr>
                                                <td><?= $item->title ?></td>
                                                <td><?= $item->title_alt ?></td>
                                                <td><?= Yii::t('generale', '{time} minutes', ['time' => $item->duration]) ?></td>
                                                <td><?= $formatter->asInteger($item->max_appointments) ?></td>
                                                <td><?= $formatter->asDecimal($item->price, 3) ?></td>
                                                <td class="action-column text-right">
                                                    <div class="action-buttons">
                                                        <button type="button" class="material-icon salamat-color mx-2" onclick="setUpdateServiceForm(<?= $item->id ?>, '<?= $item->title ?>', '<?= $item->title_alt ?>', <?= $item->duration ?>, <?= $item->price ?>, '<?= $item->max_appointments ?>');" data-toggle="modal" data-target="#branch-update-service">edit</button>
                                                        <button type="button" class="material-icon salamat-color mx-2" onclick="setDeleteServiceID(<?= $item->id ?>);" data-toggle="modal" data-target="#branch-delete-service">delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="mdc-button-group direction-reverse p-0">
                            <?= Html::button(Html::tag('div', 'add', ['class'=> 'icon material-icon']).Yii::t('general', 'New service'), [
                                'class' => 'mdc-button btn-outlined salamat-color',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#branch-add-service',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="branch-delete-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'id' => 'delete-service-form',
            'action' => ['delete-service'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Delete service') ?></div>
            </div>
            <div class="modal-body">
                <?= Yii::t('clinic', 'Service record will be deleted permanently. This action cannot be undone.') ?>
                <input type="hidden" class="service-id" name="id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" onclick="clearDeleteServiceID();" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Delete service'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="branch-update-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['update-service'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Update service') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($serviceModel, 'id', ['selectors' => ['input' => '#update-clinic-service-id']])->hiddenInput(['id' => 'update-clinic-service-id']) ?>
                <?= $form->field($serviceModel, 'title', ['selectors' => ['input' => '#update-clinic-service-title']])->textInput(['id' => 'update-clinic-service-title', 'autocomplete' => 'off']) ?>
                <?= $form->field($serviceModel, 'title_alt', ['selectors' => ['input' => '#update-clinic-service-title_alt']])->textInput(['id' => 'update-clinic-service-title_alt', 'autocomplete' => 'off']) ?>
                <div class="row">
                    <div class="col">
                        <?= $form->field($serviceModel, 'duration', ['selectors' => ['input' => '#update-clinic-service-duration']])->dropdownList([
                            "15" => Yii::t('general', '{time} minutes', ['time' => 15]),
                            "30" => Yii::t('general', '{time} minutes', ['time' => 30]),
                            "45" => Yii::t('general', '{time} minutes', ['time' => 45]),
                            "60" => Yii::t('general', '{time} minutes', ['time' => 60]),
                            "90" => Yii::t('general', '{time} minutes', ['time' => 90]),
                            "120" => Yii::t('general', '{time} minutes', ['time' => 120]),
                        ], [
                            'id' => 'update-clinic-service-duration',
                            'class' => 'form-control bootstrap-select',
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= $form->field($serviceModel, 'price', ['selectors' => ['input' => '#update-clinic-service-price']])->textInput(['id' => 'update-clinic-service-price', 'autocomplete' => 'off']) ?>
                    </div>
                </div>
                <?= $form->field($serviceModel, 'max_appointments', ['selectors' => ['input' => '#update-clinic-service-max-app']])->textInput(['id' => 'update-clinic-service-max-app', 'autocomplete' => 'off']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" onclick="clearUpdateServiceForm();" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Update service'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="branch-add-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['add-service', 'id' => $model->id],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Add new service') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($serviceModel, 'title')->textInput(['autocomplete' => 'off']) ?>
                <?= $form->field($serviceModel, 'title_alt')->textInput(['autocomplete' => 'off']) ?>
                <div class="row">
                    <div class="col">
                        <?= $form->field($serviceModel, 'duration')->dropdownList([
                            "15" => Yii::t('general', '{time} minutes', ['time' => 15]),
                            "30" => Yii::t('general', '{time} minutes', ['time' => 30]),
                            "45" => Yii::t('general', '{time} minutes', ['time' => 45]),
                            "60" => Yii::t('general', '{time} minutes', ['time' => 60]),
                            "90" => Yii::t('general', '{time} minutes', ['time' => 90]),
                            "120" => Yii::t('general', '{time} minutes', ['time' => 120]),
                        ], [
                            'class' => 'form-control bootstrap-select',
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= $form->field($serviceModel, 'price')->textInput(['autocomplete' => 'off']) ?>
                    </div>
                </div>
                <?= $form->field($serviceModel, 'max_appointments')->textInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Add service'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>