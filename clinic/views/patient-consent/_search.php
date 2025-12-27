
<?php

use clinic\models\Patient;
use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Branch;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
$patientDropDownData = [];
$patientList = Patient::find()->joinWith(['clinicPatient'])->asArray()->all();
foreach ($patientList as $patient) {
    $patientDropDownData[$patient['id']] = $patient['name'].' ('.$patient['cpr'].')';
}
$consentDropdown = [];
$consentList = \app\models\ConsentForm::find()->asArray()->all();
foreach ($consentList as $consent) {
    $consentDropdown[$consent['id']] = $consent['name'];
}
$activeClinic = Yii::$app->user->identity->active_clinic;
$branches = Branch::find()->where(['clinic_id' => $activeClinic])->select('name')->indexBy('id')->column();
?>

<div class="col-auto align-self-center mb-3">
    <?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
        'class' => 'mdc-button btn-outlined salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#invoice-search',
        ],
    ]) ?>
</div>

<div class="col-12">


</div>

<div class="modal fade" id="invoice-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'modal-content'],
        ]); ?>
        <div class="modal-header">
            <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'patient_id')->dropdownList($patientDropDownData, [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        'data-live-search' => 'true',
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'consent_id')->dropdownList($consentDropdown, [
                        'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        'data-live-search' => 'true',
                    ]) ?>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
            <?= Html::submitButton(Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
