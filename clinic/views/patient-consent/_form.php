<?php

use clinic\models\Patient;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PatientConsent */
/* @var $consentModel app\models\ConsentForm */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(BaseUrl::home() . 'js/common.js?='. microtime(), ['depends' => [\yii\web\JqueryAsset::className()]]);
$model->signature = '';
$patientList = Patient::find()->joinWith(['clinicPatient'])->asArray()->all();

$consentList = \app\models\ConsentForm::find()->asArray()->all();
$patientDropDownData = [];
foreach ($patientList as $patient) {
    $patientDropDownData[$patient['id']] = $patient['name'].' ('.$patient['cpr'].')';
}
$consentListData = [];
foreach ($consentList as $consent) {
    $consentListData[$consent['id']] = $consent['name'];
}
$display = ($consentModel->template_type == 5) ? 'block' : 'none'
?>
<style>
    $roopairs-teal: hsl(165.9, 72.5%, 47.1%);
    $roopairs-green: hsl(140.2, 50.2%, 58.2%);
    $roopairs-lightgrey: hsl(0, 0%, 87.1%);
    $roopairs-shadow: hsla(204, 4.4%, 44.7%, 0.17);

    html, body {
        background-color: $roopairs-lightgrey;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        padding: 4em;
    }

    .button {
        display: block;
        width: 20em;
        height: 2.5em;
        border-radius: 7px;
        padding: 0.75em;
        margin-bottom: 1em;

        line-height: 1em;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;

        font-size: 1rem;
        font-weight: bold;
        font-family: 'Nunito', sans-serif;
    }

    .button:hover {
        text-decoration: none;
    }

    .button:focus,
    .button.focus {
        outline: none;
    }

    .button-primary, header {
        background: linear-gradient(
        to right,
        $roopairs-teal,
        $roopairs-green,
        );
        color: white !important;
    }

    .button-primary:hover {
        background: linear-gradient(
                to right,
                lighten($roopairs-teal, 5%),
                lighten($roopairs-green, 5%),
        );
    }

    .button-primary:active,
    .button-primary.active
    {
        background: linear-gradient(
                to right,
                desaturate(darken($roopairs-teal, 5%), 10%),
                desaturate(darken($roopairs-green, 5%), 10%),
        );
        box-shadow: 0 6px 6px 0 $roopairs-shadow;
    }

    .button-primary:focus,
    .button-primary.focus
    {
        background: linear-gradient(
                to right,
                desaturate(darken($roopairs-teal, 5%), 10%),
                desaturate(darken($roopairs-green, 5%), 10%),
        );
    }


    html, body {
        width: 100%;
        height: 100%;

        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #ecf0f1;
    }

    header {
        width: 100%;
        height: 4em;
        margin-bottom: 4em;
        text-align: center;
        padding-top: 1em;
    }

    canvas {
        width: 100%;
        height: 100%;

        border: 1px #5fca83 dashed;
        border-radius: 7px;
    }

    .signature-pad-container {
        width: 70vw;
        height: 50vh;
        max-height: 30em;
        min-width: 40em;
        min-height: 25em;
        padding: 2em;

        position: relative;

        background-color: white;
        box-shadow: 0 0 20px 1px #ddd;

        text-align: right;
    }

    #clear_button {
        z-index: 10;
        position: absolute;
        right: 2em;
        padding: 1.5em 2em;

        color: #21cfa6;
        font-weight: 600;
        font-size: 14pt;
        cursor: pointer;
    }

    #finish_button {
        margin-top: 2em;
        cursor: pointer;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js" integrity="sha256-W+ivNvVjmQX6FTlF0S+SCDMjAuTVNKzH16+kQvRWcTg=" crossorigin="anonymous"></script>
<div class="card raised-card employee-form">

    <?php $form = ActiveForm::begin([

    ]); ?>

    <div class="card-body pb-0">
        <div class="row">
            <div class="col-md-6">

                <?= $form->field($model, 'patient_id')->dropdownList($patientDropDownData, [
                    'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                    'class' => 'form-control bootstrap-select',
                    'data-live-search' => 'true',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'consent_id')->dropdownList($consentListData, [
                    'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                    'class' => 'form-control bootstrap-select',
                    'data-live-search' => 'true',
                ]) ?>
            </div>
        </div>
        <?php
            if ($consentModel->template_type == 2) {
        ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'cpr')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control',
                ]) ?>
            </div>
        </div>
        <?php

            }
        ?>
        <?php
        if ($consentModel->template_type == 3 || $consentModel->template_type == 4) {
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'private_number')->textInput([
                        'autocomplete' => 'off',
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>
            <?php

        }
        ?>
        <?php
        if ($consentModel->template_type == 5) {
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'doctor_name')->textInput([
                        'autocomplete' => 'off',
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>
            <?php

        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'consent_date')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>

    <div class="card-body pb-0">
        <h5 class="mb-3 "><?= Yii::t('general', 'Consent Details: ') ?></h5>
        <div class="row">
            <?= $consentModel->content ?>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body pb-0">
        <?= $form->field($model, 'signature')->hiddenInput(['maxlength' => true]) ?>
        <div class="signature-pad-container">
            <a class="button-text" id="clear_button">CLEAR</a>
            <canvas id="signature_pad"></canvas>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body pb-0" style="display: <?= $display ?>">
        <?= $form->field($model, 'doctor_signature')->hiddenInput(['maxlength' => true]) ?>
        <div class="signature-pad-container">
            <a class="button-text" id="clear_button1">CLEAR</a>
            <canvas id="signature_pad1"></canvas>
        </div>
    </div>

    <div class="mdc-button-group direction-reverse p-2">
                        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color','id' => 'save-consent']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>
 

    <?php ActiveForm::end(); ?>

</div>
<script>

</script>
