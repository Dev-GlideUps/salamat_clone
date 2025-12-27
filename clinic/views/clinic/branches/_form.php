<?php

// use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\models\WorkingHoursForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container-custom">
    <div class="row">
        <div class="col">

            <?php $form = ActiveForm::begin(['options' => ['class' => 'has-workinghours']]); ?>
            <div class="card raised-card">
                <div class="card-body">
                    <h5 class="card-title mb-4"><?= Yii::t('clinic', 'Update branch') ?></h5>
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-md-6">
                            <?= $form->field($model, 'name_alt')->textInput() ?>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <?= $form->field($model, 'phone')->textInput() ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="mb-4"><?= Yii::t('clinic', 'Appointments schedule') ?></h6>
                    <?php $startingHours = [
                        '00:00:00' => '12 AM',
                        '01:00:00' => '1 AM',
                        '02:00:00' => '2 AM',
                        '03:00:00' => '3 AM',
                        '04:00:00' => '4 AM',
                        '05:00:00' => '5 AM',
                        '06:00:00' => '6 AM',
                        '07:00:00' => '7 AM',
                        '08:00:00' => '8 AM',
                        '09:00:00' => '9 AM',
                        '10:00:00' => '10 AM',
                        '11:00:00' => '11 AM',
                        '12:00:00' => '12 PM',
                        '13:00:00' => '1 PM',
                        '14:00:00' => '2 PM',
                        '15:00:00' => '3 PM',
                        '16:00:00' => '4 PM',
                        '17:00:00' => '5 PM',
                        '18:00:00' => '6 PM',
                        '19:00:00' => '7 PM',
                        '20:00:00' => '8 PM',
                    ]; ?>
                    <?php $endingHours = [
                        '08:00:00' => '8 AM',
                        '09:00:00' => '9 AM',
                        '10:00:00' => '10 AM',
                        '11:00:00' => '11 AM',
                        '12:00:00' => '12 PM',
                        '13:00:00' => '1 PM',
                        '14:00:00' => '2 PM',
                        '15:00:00' => '3 PM',
                        '16:00:00' => '4 PM',
                        '17:00:00' => '5 PM',
                        '18:00:00' => '6 PM',
                        '19:00:00' => '7 PM',
                        '20:00:00' => '8 PM',
                        '21:00:00' => '9 PM',
                        '22:00:00' => '10 PM',
                        '23:00:00' => '11 PM',
                        '00:00:00' => '12 AM',
                    ]; ?>
                    <div class="row">
                        <div class="col-xl-3 col-md-4">
                            <?= $form->field($model, 'schedule_starting')->dropDownList($startingHours, [
                                "class" => "form-control bootstrap-select",
                            ]) ?>
                        </div>
                        <div class="col-xl-3 col-md-4">
                            <?= $form->field($model, 'schedule_ending')->dropDownList($endingHours, [
                                "class" => "form-control bootstrap-select",
                            ]) ?>
                        </div>
                        <div class="col-xl-3 col-md-4">
                            <?= $form->field($model, 'auto_closing')->dropDownList($model::getClosingTime(), [
                                "class" => "form-control bootstrap-select",
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="m-0"><?= Yii::t('clinic', 'Working hours') ?></h6>
                </div>
                <div class="mdc-list-container working-hours-form-container">
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
            </div>

            <div class="mdc-fab">
                <button type="submit" class="mdc-fab-button extended bg-salamat-color">
                    <div class="icon material-icon">save</div>
                    <div class="label"><?= Yii::t('general', 'Save') ?></div>
                </button>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
