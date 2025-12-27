<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model patient\models\Patient */
/* @var $form yii\widgets\ActiveForm */

$cprError = !empty($model->errors['cpr']);
$country = new Country();

if (!empty($model->photo)) {
    $this->RegisterJs('
        $("#patient-imagefile").siblings(".custom-file-label").first().addClass("has-photo").attr("data-photo", "'.$model->photoUrl.'").prop("style", "background-image: url(\''.$model->photoUrl.'\');");
    ', $this::POS_END);
}

?>

<div class="patient-form">
    <div class="card raised-card mb-4">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-sm">
                    <div class="personal-photo-input text-center">
                        <?= $form->field($model, 'imageFile')->fileInput() ?>
                    </div>
                </div>
            </div>
            <h6 class="mb-3"><?= Yii::t('patient', 'Patient information') ?></h6>
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-2 col-md-3">
                    <?= $form->field($model, 'name_alt')->textInput(['autocomplete' => 'off']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($model, 'cpr')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($model, 'nationality')->dropdownList($country->countriesList, [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        'data-live-search' => 'true',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($model, 'dob')->textInput([
                        'autocomplete' => 'off',
                        'class' => 'form-control bootstrap-datepicker',
                        'data-date-end-date' => date('Y-m-d'),
                        'data-date-start-view' => 'years',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($model, 'marital_status')->dropdownList($model::statusList(), [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="mdc-divider"></div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <?= $form->field($model, 'address')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-2 col-md-3">
                    <?= $form->field($model, 'gender')->dropdownList($model::genderList(), [
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <?= $form->field($model, 'phone')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-2 col-md-3">
                    <?= $form->field($model, 'blood_type')->dropdownList($model::bloodTypes(), [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]) ?>
                </div>
                <div class="col-lg-2 col-md-3">
                    <?= $form->field($model, 'height')->textInputTextAppend(['autocomplete' => 'off', 'text-append' => Yii::t('general', 'cm')]) ?>
                </div>
                <div class="col-lg-2 col-md-3">
                    <?= $form->field($model, 'weight')->textInputTextAppend(['autocomplete' => 'off', 'text-append' => Yii::t('general', 'Kg')]) ?>
                </div>
            </div>
        </div>
        <div class="mdc-divider"></div>
        <div class="card-body">
            <h6 class="mb-3"><?= Yii::t('patient', 'Emergency contact') ?></h6>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'relative_name')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?= $form->field($model, 'relative_phone')->textInput(['autocomplete' => 'off']) ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($model, 'relative_relation')->dropdownList($model::relativeList(), [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]) ?>
                </div>
            </div>
            <div class="mdc-button-group direction-reverse p-0">
                <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button salamat-color']) ?>
                <?php if ($model->isNewRecord) { ?>
                    <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button salamat-color']) ?>
                <?php } else { ?>
                    <?= Html::a(Yii::t('general', 'Cancel'), ['view', 'id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                <?php } ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
