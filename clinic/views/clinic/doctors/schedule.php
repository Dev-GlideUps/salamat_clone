<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\models\WorkingHoursForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Doctor */

$this->title = $doctor->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Doctors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $doctor->name, 'url' => ['view', 'id' => $doctor->id]];
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Update Schedule');

$this->RegisterJs('
    $("#doctorclinicbranch-branch_override").change(function() {
        if ($(this).is(":checked")) {
            $(".working-hours-form-container").slideDown();
        } else {
            $(".working-hours-form-container").slideUp();
        }
    });
', $this::POS_END);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="mdc-list-item mb-3">
                <div class="graphic" style="background-image: url('<?= $doctor->photoThumb ?>');"></div>
                <div class="text"><?= $doctor->name ?>
                    <div class="secondary"><?= $doctor->name_alt ?></div>
                </div>
            </div>
            
            <div class="card raised-card doctor-schedule">
                <?php $form = ActiveForm::begin(['options' => ['class' => 'has-workinghours']]); ?>
                <div class="card-body">
                    <?php if ($model->isNewRecord) { ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'branch_id')->dropDownList($branches, [
                                'class' => 'form-control bootstrap-select',
                                'data-live-search' => 'true',
                            ]) ?>
                        </div>
                    </div>
                    <?php } else { ?>
                        <h5 class="salamat-color"><?= $model->branch->clinic->name . " - " . $model->branch->name ?></h5>
                    <?php } ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'status')->dropDownList($model::statusList(), [
                                'class' => 'form-control bootstrap-select',
                                // 'data-live-search' => 'true',
                            ])->hint(Yii::t('clinic', 'If doctor status is unavailable, no more appointments can be created for the doctor in this branch.')) ?>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0">
                    <h6><?= Yii::t('clinic', 'Working hours') ?></h6>
                    <?= $form->field($model, 'branch_override')->checkbox()->hint(Yii::t('clinic', "Doctor appointments uses branch's working hours by default. you can customize doctor's working hours by overriding branch schedule.")) ?>
                </div>
                <div class="mdc-list-container working-hours-form-container" <?= $model->branch_override ? '' : 'style="display: none;"' ?>>
                    <?php
                    $inputTemplate = [
                        'template' => '{input}',
                        'inputOptions' => ['class' => 'form-control bootstrap-timepicker'],
                    ];
                    foreach ($model->weekDays as $dayNum => $dayState) {
                    ?>
                    <div class="mdc-list-group <?= (bool) $dayState ? 'expanded' : 'collapsed' ?>">
                        <button type="button" class="mdc-list-item salamat-color">
                            <div class="icon text-hint text-uppercase mr-0">
                                <div class="material-icon day-status-on">check_box</div>
                                <div class="material-icon day-status-off">check_box_outline_blank</div>
                            </div>
                            <div class="text">
                                <?= WorkingHoursForm::DAYS[$dayNum] ?>
                                <span class="text-secondary mdt-overline day-status-on"><b>(<?= Yii::t('general', 'Open') ?>)</b></span>
                                <span class="text-secondary mdt-overline day-status-off"><b>(<?= Yii::t('general', 'Closed') ?>)</b></span>
                            </div>
                            <div class="meta icon">
                                <div class="material-icon text-hint">keyboard_arrow_down</div>
                            </div>
                        </button>
                        <?= $form->field($model, "weekDays[$dayNum]")->hiddenInput(['class' => "form-control week-day-$dayNum", 'value' => (int) $dayState]) ?>
                        <div class="mdc-dropdown working-hours-form" data-weekday="<?= $dayNum ?>">
                            <div class="working-hours-container">
                            <?php $index = -1;
                            if (is_array($workingHours[$dayNum])) {
                                foreach ($workingHours[$dayNum] as $workingHoursModel) {
                                    $index++; ?>
                                    <div class="mdc-list-item working-hours-row" data-index="<?= $index ?>">
                                        <div class="text">
                                            <?= $form->field($workingHoursModel, "[$dayNum][$index]from", $inputTemplate)->textInput() ?>
                                            <div class="material-icon text-hint">minimize</div>
                                            <?= $form->field($workingHoursModel, "[$dayNum][$index]to", $inputTemplate)->textInput() ?>
                                        </div>
                                        <div class="meta icon">
                                            <button type="button" class="material-icon delete-row">close</button>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                            </div>
                            <div class="mdc-list-item working-24-hours <?= $index > -1 ? 'd-none' : '' ?>">
                                <div class="text"><b><?= Yii::t('general', 'Open 24 hours') ?></b></div>
                            </div>
                            <div class="px-3">
                                <div class="mdc-button-group">
                                    <button type="button" class="mdc-button text-secondary add-set-of-hours">
                                        <div class="icon material-icon">add</div>
                                        <?= Yii::t('general', 'Add a set of hours') ?>
                                    </button>
                                    <?php if ($dayNum == 7) { ?>
                                    <button type="button" class="mdc-button text-secondary apply-to-all-week" onclick="applyWorkingHoursToAllDays()">
                                        <div class="icon material-icon">filter_none</div>
                                        <?= Yii::t('general', 'Apply to all days') ?>
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="mdc-button-group direction-reverse p-3">
                    <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                    <?= Html::a(Yii::t('general', 'Cancel'), ['view', 'id' => $model->doctor_id, '#' => "branch-$model->branch_id"], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>