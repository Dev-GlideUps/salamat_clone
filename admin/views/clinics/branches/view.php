<?php

use yii\helpers\Html;
use common\grid\DetailView;
use common\models\WorkingHoursForm;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */

$this->title = "{$model->clinic->name} - {$model->name}";
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$services = $model->services;
$formatter = Yii::$app->formatter;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/door_open.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

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
                                'id',
                                [
                                    'attribute' => 'clinic_id',
                                    'value' => function ($model) {
                                        return Html::a($model->clinic->name, ['/clinics/view', 'id' => $model->clinic_id]);
                                    },
                                    'format' => 'raw',
                                ],
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
                                        return Html::a($model->address, $model->locationUrl, [
                                            'target' => '_blank',
                                        ]);
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'location',
                                    'value' => function ($model) {
                                        $col1 = [];
                                        $col2 = [];
                                        foreach ($model->coordinates as $key => $value) {
                                            $col1[] = Html::tag('div', ucfirst($key), ['class' => 'kt-label-font-color-2']);
                                            $col2[] = Html::tag('div', ucfirst($value));
                                        }
                
                                        return Html::tag(
                                            'div',
                                            Html::tag('div', implode("\n", $col1), ['class' => 'col-auto']).
                                            Html::tag('div', implode("\n", $col2), ['class' => 'col-auto']),
                                            ['class' => 'row']
                                        );
                                    },
                                    'format' => 'raw',
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
                            <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                            <?= Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                                'class' => 'mdc-button btn-outlined salamat-color',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#delete-record',
                                ],
                            ]) ?>
                             <?php $title = $model->block == 2 ? Yii::t('general', 'UnBlock') : Yii::t('general', 'BLOCK') ?>
                            <?= Html::button(Html::tag('div', 'block', ['class' => 'icon material-icon']) . $title, [
                                'class' => 'mdc-button btn-outlined ',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => "#blocked-state-$model->id",
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="card-body tab-pane fade" id="branch-services" role="tabpanel">
                        <?php if (empty($services)) { ?>
                            <div class="py-5 text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No Services!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="mdc-datatable">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><span><?= $services[0]->getAttributeLabel('title') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('title_alt') ?></span></th>
                                                <th><span><?= $services[0]->getAttributeLabel('duration') ?></span></th>
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
                                                <td><?= $formatter->asDecimal($item->price, 3) ?></td>
                                                <td class="action-column text-right">
                                                    <div class="action-buttons">
                                                        <button type="button" class="material-icon salamat-color mx-2" onclick="setUpdateServiceForm(<?= $item->id ?>, '<?= $item->title ?>', '<?= $item->title_alt ?>', <?= $item->duration ?>, <?= $item->price ?>);" data-toggle="modal" data-target="#branch-update-service">edit</button>
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
                                'class' => 'mdc-button salamat-color',
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

<div class="modal fade" id="delete-record" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Delete branch') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('clinic', 'All branch records and child records will be deleted from the database. this action cannot be undone.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a(Yii::t('clinic', 'Delete branch'), ['delete', 'id' => $model->id], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Add service'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<div class="modal fade" id="blocked-state-<?= $model->id ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title"><?= $model->block == 1 ? Yii::t('general', 'Block Branch') : Yii::t('general', 'UNBLOCK Branch') ?></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                            <?= Html::a($model->block == 1 ? 'Block' : 'Unblock', [$model->block == 1 ? 'branch-block' : 'un-block',   'id' => $model->id], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
                        </div>
                    </div>
                </div>
            </div>