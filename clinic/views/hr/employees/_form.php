<?php

use yii\helpers\Html;
use common\models\Country;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\hr\Employee */
/* @var $form yii\widgets\ActiveForm */

$country = new Country();
?>

<div class="card raised-card employee-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body pb-0">
        <h5 class="mb-3"><?= Yii::t('general', 'Personal information') ?></h5>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="col-lg-3 col-md-4">
                <?= $form->field($model, 'phone')->textInput(['autocomplete' => 'off']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <?= $form->field($model, 'cpr')->textInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="col-lg-3 col-md-4">
                <?= $form->field($model, 'cpr_expiry')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                    'data-date-start-date' => date('Y-m-d'),
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7 col-md-8">
                <?= $form->field($model, 'address')->textInput(['autocomplete' => 'off']) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body pb-0">
        <h5 class="mb-3"><?= Yii::t('hr', 'Employee contract') ?></h5>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'salary')->textInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'contract_start')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'contract_expiry')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body pb-0">
        <h5 class="mb-3"><?= Yii::t('general', 'Passport') ?></h5>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'nationality')->dropdownList($country->countriesList, [
                    'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                    'class' => 'form-control bootstrap-select',
                    'data-live-search' => 'true',
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'passport_start')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'passport_expiry')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body pb-0">
        <h5 class="mb-3"><?= Yii::t('hr', 'Foreign employees') ?></h5>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'visa_expiry')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'residency_start')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'residency_expiry')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body">
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
