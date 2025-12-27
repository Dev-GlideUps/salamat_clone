<?php

use yii\helpers\Html;
use yii\helpers\Markdown;
use common\grid\DetailView;
use common\models\WorkingHoursForm;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Doctor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Doctors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
function deleteSchedule(branch_id) {
    $('#delete-schedule input.schedule-branch_id').val(branch_id);
}

$(document).ready(function() {
    $('#doctor-schedule-tabs .nav-item .nav-link').first().trigger('click');
});
JS;

$this->registerJs($script, $this::POS_END);

$formatter = Yii::$app->formatter;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="doctor-profile">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="personal-photo mt-5">
                    <img src="<?= $model->photoUrl ?>">
                </div>
                <h5 class="text-center"><?= $model->name ?></h5>
                <h6 class="text-secondary text-center d-none"><?= $model->name_alt ?></h6>
                <p class="text-secondary text-center"><?= $model->specialization->title ?></p>
                
                <div class="mdc-button-group direction-stack">
                    <?= Html::a(Html::tag('div', 'update', ['class' => 'material-icon icon']).Yii::t('clinic', 'Update'), ["update", 'id' => $model->id], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                </div>
            </div>
            <div class="col">
                <div class="card raised-card">
                    <ul class="nav nav-tabs" id="doctor-profile-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#doctor-info" role="tab" aria-selected="true"><?= Yii::t('clinic', 'Doctor information') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#doctor-description" role="tab" aria-selected="false"><?= Yii::t('general', 'Biography') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="card-body tab-pane fade active show" id="doctor-info" role="tabpanel">
                            <?= DetailView::widget([
                                'labelColumn' => 'col-lg-4 col-md-4',
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    'name',
                                    'name_alt',
                                    'mobile',
                                    [
                                        'attribute' => 'speciality',
                                        'value' => function ($model) {
                                            return $model->specialization->title;
                                        },
                                    ],
                                    'experience:date',
                                    [
                                        'attribute' => 'language',
                                        'value' => function ($model) {
                                            return $model->languagesText;
                                        },
                                    ],
                                    // 'photo',
                                    [
                                        'attribute' => 'user_id',
                                        'value' => function ($model) {
                                            if (empty($model->user_id)) {
                                                return null;
                                            }

                                            return Html::tag('div', Html::tag('div', $model->user->name.Html::tag('div', $model->user->email, ['class' => 'secondary']), ['class' => 'text m-0']), ['class' => 'mdc-list-item']);
                                        },
                                        'format' => 'html',
                                    ],
                                    'created_at:datetime',
                                    'updated_at:datetime',
                                ],
                            ]) ?>
                        </div>
                        <div class="card-body tab-pane fade" id="doctor-description" role="tabpanel">
                            <?php if (empty($model->description)) { ?>
                                <div class="py-5 text-center">
                                    <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                        <h5 class="text-hint my-3"><?= Yii::t('general', 'No biography') ?></h5>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <?= Markdown::process($model->description) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5 class="mt-4"><?= Yii::t('clinic', 'Branches schedule') ?></h5>
                <?php
                $tabs = [];
                $contents = [];
                foreach ($model->doctorSchedule as $schedule) {
                    $tabs[] = '<li class="nav-item">';
                    $tabs[] = '<a class="nav-link" data-toggle="tab" href="#branch-'.$schedule->branch_id.'" role="tab" aria-selected="false">'.$schedule->branch->name.'</a>';
                    $tabs[] = '</li>';
                    
                    $contents[] = '<div class="tab-pane fade" id="branch-'.$schedule->branch_id.'" role="tabpanel">';
                    $contents[] = '<div class="card-body">';
                    $contents[] = '<table class="table table-sm table-borderless">';
                    $contents[] = '<tbody>';
                    $contents[] = '<tr>';
                    $contents[] = '<td class="text-secondary" style="width: 25%;">'.$schedule->branch->getAttributeLabel('clinic_id').'</td>';
                    $contents[] = '<td>'."{$schedule->branch->clinic->name} - {$schedule->branch->name}".'</td>';
                    $contents[] = '</tr>';
                    $contents[] = '<tr>';
                    $contents[] = '<td class="text-secondary">'.$schedule->getAttributeLabel('status').'</td>';
                    $contents[] = '<td>'.$schedule::statusList()[$schedule->status].'</td>';
                    $contents[] = '</tr>';
                    $contents[] = '<tr>';
                    $contents[] = '<td class="text-secondary">'.$schedule->getAttributeLabel('branch_override').'</td>';
                    $contents[] = '<td><div class="material-icon">'.($schedule->branch_override ? 'check' : 'close').'</div></td>';
                    $contents[] = '</tr>';
                    $contents[] = '</tbody>';
                    $contents[] = '</table>';

                    $contents[] = WorkingHoursForm::renderWorkingHoursTable($schedule->getWorkingHours(true));
                    
                    $contents[] = '<div class="mdc-button-group direction-reverse p-0">';
                    $contents[] = Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('clinic', 'Update schedule'), ['schedule', 'doctor_id' => $schedule->doctor_id, 'branch_id' => $schedule->branch_id], ['class' => 'mdc-button salamat-color']);
                    $contents[] = Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('clinic', 'Delete schedule'), [
                        'class' => 'mdc-button salamat-color',
                        'onclick' => "deleteSchedule($schedule->branch_id);",
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#delete-schedule',
                        ],
                    ]);
                    $contents[] = '</div>';

                    $contents[] = '</div>';
                    
                    // Services
                    $services = $schedule->doctorServices;
                    $contents[] = '<div class="mdc-divider"></div>';
                    $contents[] = '<div class="card-body">';
                    $contents[] = '<h6>'.Yii::t('clinic', 'Doctor services').'</h6>';

                    if (empty($services)) {
                        $contents[] = '<div class="text-center">';
                        $contents[] = '<div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">';
                        $contents[] = file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg'));
                        $contents[] = '<h5 class="text-hint my-3">'.Yii::t('clinic', 'No Services!').'</h5>';
                        $contents[] = '<p class="text-hint my-3">'.Yii::t('clinic', 'Branch services will be used in place of doctor services').'</p>';
                        $contents[] = '</div>';
                        $contents[] = '</div>';
                    } else {
                        $contents[] = '<p class="text-secondary">'.Yii::t('clinic', 'Doctor services will override branch services').'</p>';
                        $contents[] = '<div class="mdc-datatable">';
                        $contents[] = '<div class="table-responsive">';
                        $contents[] = '<table class="table table-hover">';
                        $contents[] = '<thead>';
                        $contents[] = '<tr>';
                        $contents[] = '<th><span>'.$services[0]->getAttributeLabel('title').'</span></th>';
                        $contents[] = '<th><span>'.$services[0]->getAttributeLabel('title_alt').'</span></th>';
                        $contents[] = '<th><span>'.$services[0]->getAttributeLabel('duration').'</span></th>';
                        $contents[] = '<th><span>'.$services[0]->getAttributeLabel('price').'</span></th>';
                        $contents[] = '<th class="action-column"><span></span></th>';
                        $contents[] = '</tr>';
                        $contents[] = '</thead>';
                        $contents[] = '<tbody>';
                        foreach ($services as $item) {
                            $contents[] = '<tr>';
                            $contents[] = '<td>'.$item->title.'</td>';
                            $contents[] = '<td>'.$item->title_alt.'</td>';
                            $contents[] = '<td>'.Yii::t('generale', '{time} minutes', ['time' => $item->duration]).'</td>';
                            $contents[] = '<td>'.$formatter->asDecimal($item->price, 3).'</td>';
                            $contents[] = '<td class="action-column text-right">';
                            $contents[] = '<div class="action-buttons">';
                            $contents[] = '<button type="button" class="material-icon salamat-color mx-2" onclick="setUpdateServiceForm('.$item->id.', \''.$item->title.'\', \''.$item->title_alt.'\', \''.$item->duration.'\', \''.$item->price.'\');" data-toggle="modal" data-target="#doctor-update-service">edit</button>';
                            $contents[] = '<button type="button" class="material-icon salamat-color mx-2" onclick="setDeleteServiceID('.$item->id.');" data-toggle="modal" data-target="#doctor-delete-service">delete</button>';
                            $contents[] = '</div>';
                            $contents[] = '</td>';
                            $contents[] = '</tr>';
                        }
                        $contents[] = '</tbody>';
                        $contents[] = '</table>';
                        $contents[] = '</div>';
                        $contents[] = '</div>';
                    }

                    $contents[] = '<div class="mdc-button-group direction-reverse p-0">';
                    $contents[] = Html::button(Html::tag('div', 'add', ['class'=> 'icon material-icon']).Yii::t('general', 'New service'), [
                        'class' => 'mdc-button salamat-color',
                        'onclick' => "setAddDoctorServiceID($schedule->branch_id)",
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#doctor-add-service',
                        ],
                    ]);
                    $contents[] = '</div>';
                    $contents[] = '</div>';
                    $contents[] = '</div>';
                }
                ?>
                <div class="card raised-card mt-4">
                    <?php if (empty($tabs)) { ?>
                    <div class="card-body">
                        <div class="py-5 text-center">
                            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                <h5 class="text-hint my-3"><?= Yii::t('clinic', 'Empty schedule!') ?></h5>
                                <?= Html::a(Yii::t('clinic', 'New branch schedule'), ['schedule', 'doctor_id' => $model->id], ['class' => 'mdc-button btn-outlined salamat-color']); ?>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="mdc-button-group direction-reverse mx-1">
                        <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('clinic', 'New branch schedule'), ['schedule', 'doctor_id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                    </div>
                    <ul class="nav nav-tabs" id="doctor-schedule-tabs" role="tablist">
                        <?= implode("\n", $tabs) ?>
                    </ul>
                    <div class="tab-content">
                        <?= implode("\n", $contents) ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-schedule" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Delete schedule') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('clinic', 'Schedule data will be deleted from the database and the doctor relation with the branch will be removed. this action cannot be undone.') ?></div>
            <?= Html::beginForm(['delete-schedule'], 'post', ['class' => 'modal-footer']); ?>
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Delete schedule'), ['class' => 'mdc-button salamat-color']) ?>
                <input type="hidden" name="doctor_id" class="schedule-doctor_id" value="<?= $model->id ?>">
                <input type="hidden" name="branch_id" class="schedule-branch_id" value="">
            <?= Html::endForm() ?>
        </div>
    </div>
</div>

<div class="modal fade" id="doctor-delete-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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

<div class="modal fade" id="doctor-update-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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

<div class="modal fade" id="doctor-add-service" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                <?= $form->field($serviceModel, 'branch_id')->hiddenInput() ?>
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