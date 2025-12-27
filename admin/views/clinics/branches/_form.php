<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Clinic;
use common\models\WorkingHoursForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */
/* @var $form yii\widgets\ActiveForm */

$clinics = Clinic::find()->select('name')->indexBy('id')->column();
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'has-workinghours']]); ?>
<div class="card-body">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <?= $form->field($model, 'clinic_id')->dropDownList($clinics, [
                'autocomplete' => 'off',
                'prompt' => ['text' => '', 'options' => ['class' => 'd-none']],
                'class' => 'form-control bootstrap-select',
                'data-live-search' => 'true',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col-lg-3 col-md-4">
            <?= $form->field($model, 'name_alt')->textInput() ?>
        </div>
        <div class="col-lg-6 col-md-7">
            <?= $form->field($model, 'address')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-3">
            <?= $form->field($model, 'coordinatesInput[latitude]')->textInput()->label(Yii::t('general', 'Latitude')) ?>
        </div>
        <div class="col-lg-2 col-md-3">
            <?= $form->field($model, 'coordinatesInput[longitude]')->textInput()->label(Yii::t('general', 'Longitude')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-4 col-md-6">
            <?= $form->field($model, 'phone')->textInput() ?>
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
<div class="mdc-divider"></div>    
<div class="mdc-button-group direction-reverse p-3">
    <?php $cancelRoute = $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id]; ?>
    <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
    <?= Html::a(Yii::t('general', 'Cancel'), $cancelRoute, ['class' => 'mdc-button btn-outlined salamat-color']) ?>
</div>
<?php ActiveForm::end(); ?>
