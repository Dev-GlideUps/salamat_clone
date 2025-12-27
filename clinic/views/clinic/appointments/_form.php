<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use common\models\Country;
use clinic\models\ClinicPatient;
use clinic\models\DoctorClinicBranch;

/* @var $this yii\web\View */
/* @var $model clinic\models\Appointment */
/* @var $form yii\widgets\ActiveForm */

$country = new Country();
$doctorsAR = DoctorClinicBranch::find()->joinWith(['doctor d', 'branch b'])->where(['status' => DoctorClinicBranch::STATUS_ACTIVE,'b.clinic_id' => Yii::$app->user->identity->active_clinic])->all();
$doctors = [];
$services = [];
$servicesOptions = [];

foreach ($doctorsAR as $item) {
    $doctors["{$item->doctor_id}-{$item->branch_id}"] = "{$item->doctor->name} - {$item->branch->name}";
    foreach ($item->services as $serviceItem) {
        $si = "{$item->doctor_id}-{$item->branch_id}-{$serviceItem->id}";
        $services[$si] = "$serviceItem->title - ".Yii::t('general', '{time} minutes', ['time' => $serviceItem->duration]);
        $servicesOptions[$si] = ['class' => "db-{$item->doctor_id}-{$item->branch_id}"];
    }
}

$view = Yii::$app->controller->action->id;
if(!$model->isNewRecord) {
    $model->doctor_branch = "{$model->doctor_id}-{$model->branch_id}";
}

if ($patient->isNewRecord) {
    $profileHint = '';
    $lastProfile = ClinicPatient::find()->where(['clinic_id' => Yii::$app->user->identity->active_clinic])->orderBy(['created_at' => SORT_DESC])->one();
    if ($lastProfile !== null && !empty($lastProfile->profile_ref)) {
        $profileHint = Yii::t('clinic', 'Last profile ID: {profile}', ['profile' => $lastProfile->profile_ref]);
        $clinicPatient->profile_ref = preg_replace_callback("|(\d+)|", function($matches) {
            foreach ($matches as $match) {
                return $match + 1;
            }
        }, $lastProfile->profile_ref);
    }
}

$cprError = !empty($patient->errors['cpr']);

$ajaxUrl = Url::to(['/clinic/appointments/available-times?view=' . $view .'&id='.$model->id]);
$csrfToekn = Yii::$app->request->getCsrfToken();
$script = <<< JS
$('#appointment-date').on('change', function () {
    $('#appointment-date').closest('.has-loading').children('.loading-block').addClass('active');

    $.ajax({
        url: '$ajaxUrl',
        type: 'post',
        data: {
            date: $('#appointment-date').val(),
            doctor_id: $('#appointment-doctor_branch').val().split('-')[0],
            branch_id: $('#appointment-doctor_branch').val().split('-')[1],
            _csrf: '$csrfToekn'
        },
        success: function (data) {
            $('#appointment-date').closest('.has-loading').children('.loading-block').removeClass('active');
            $('#appointment-time').html(data);
        },
        error: function (data) {
            var html = '<div class="col text-center"><div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;"><h5 class="text-hint my-3">Not available!</h5><p class="text-hint p-0 error-message">'+data.responseText+'</p></div></div>';
            $('#appointment-date').closest('.has-loading').children('.loading-block').removeClass('active');
            $('#appointment-time').html(html);
        }
    });
});

$('#appointment-service_id').on('show.bs.select', function (e) {
    var value = $('#appointment-doctor_branch').val();
    $('.field-appointment-service_id .bootstrap-select .dropdown-menu li a').each(function (i) {
        if ($(this).hasClass('db-'+value)) {
            $(this).parent().removeClass('d-none');
        } else {
            $(this).parent().addClass('d-none');
        }
    });
});

$('#appointment-doctor_branch').on('change', function () {
    if('$view' !== "update") {
        $('#appointment-service_id').val('').trigger('change');     
    }
    $('#appointment-date').trigger('change');
});

function reloadAppointmentForm() {
    $('#appointment-doctor_branch').trigger('change');
}

$(document).ready(function () {
    reloadAppointmentForm();
});
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="appointment-form">
    <?php if ($model->isNewRecord) { ?>
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card raised-card">
                <?= Html::beginForm(['create'], 'get', ['class' => 'card-body']) ?>
                    <div class="form-label-group form-group">
                        <input type="text" id="patient-filter-cpr" class="form-control <?= $patient->isNewRecord || $cprError ? 'is-invalid' : 'is-valid' ?>" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'CPR') ?>" value="<?= $patient->cpr ?>">
                        <label for="patient-cpr"><?= Yii::t('patient', 'CPR') ?></label>
                        <div class="invalid-feedback"><?= $cprError ? $patient->errors['cpr'][0] : '' ?></div>
                    </div>
                    <div class="form-label-group form-group">
                        <select class="form-control bootstrap-select" name="nationality" id="patient-filter-nationality" data-live-search="true">
                            <?php foreach($country->countriesList  as $key => $value) {
                                $options = [
                                    'class' => 'font-italic',
                                    'value' => $key,
                                ];
                                if($key == $patient->nationality) {
                                    $options['selected'] = true;
                                }
                                echo Html::tag('option', $value, $options);
                            } ?>
                        </select>
                        <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                    </div>

                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Html::tag('div', 'search', ['class' => 'icon material-icon']).Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
                    </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <p class="text-secondary salamat-dark my-4"><?= $patient->isNewRecord ? Yii::t('patient', "Couldn't find patient. please check the CPR and Nationality again or create a new patient record.") : '' ?></p>

    <?php $form = ActiveForm::begin(); ?>
        <div class="card raised-card mb-4">
            <?php if ($patient->isNewRecord) { ?>
            <div class="card-body">
                <h6 class="mb-3"><?= Yii::t('patient', 'New patient information') ?></h6>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <?= $form->field($patient, 'name')->textInput(['autocomplete' => 'off']) ?>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <?= $form->field($patient, 'name_alt')->textInput(['autocomplete' => 'off']) ?>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <?= $form->field($clinicPatient, 'profile_ref')->textInput(['autocomplete' => 'off'])->hint($profileHint) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <?= $form->field($patient, 'cpr')->textInput(['autocomplete' => 'off']) ?>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <?= $form->field($patient, 'nationality')->dropdownList($country->countriesList, [
                            'prompt' => ['text' => Yii::t('general', 'None'), 'options' => ['class' => 'font-italic']],
                            'class' => 'form-control bootstrap-select',
                            'data-live-search' => 'true',
                        ]) ?>
                    </div>
                </div>
                <h6 class="mb-3"><?= Yii::t('patient', 'Contact number') ?></h6>
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <?= $form->field($patient, 'phone_line')->dropdownList($country::phoneLines(), [
                            'class' => 'form-control bootstrap-select',
                            'data-live-search' => 'true',
                        ]) ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <?= $form->field($patient, 'phone')->textInput(['autocomplete' => 'off']) ?>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <h6 class="p-4 m-0"><?= Yii::t('patient', 'Patient information') ?></h6>

            <?php if($model->isNewRecord) { ?>
                <?php if ($patient->clinicPatient == null) { ?>
                <div class="card-body bg-salamat-secondary">
                    <div class="media text-primary">
                        <div class="media-icon mr-3"><span class="material-icon">new_releases</span></div>
                        <div class="media-body"><div class="mdt-body"><?= Yii::t('patient', 'New patient: this patient does not have a profile. a new profile will be created automatically.') ?></div></div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="card-body bg-salamat-color">
                    <div class="media text-primary">
                        <div class="media-icon mr-3"><span class="material-icon">done_all</span></div>
                        <div class="media-body"><div class="mdt-body"><?= Yii::t('patient', 'Existing patient: patient profile created on {date}', ['date' => Yii::$app->formatter->asDate($patient->clinicPatient->created_at)]) ?></div></div>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-secondary" style="width: 25%;"><?= $patient->getAttributeLabel('cpr') ?></td>
                            <td><?= $patient->cpr ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary"><?= $patient->getAttributeLabel('nationality') ?></td>
                            <td><?= $country->countriesList[$patient->nationality]  ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary"><?= $patient->getAttributeLabel('name') ?></td>
                            <td><?= $patient->name ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary"><?= $patient->getAttributeLabel('name_alt') ?></td>
                            <td><?= $patient->name_alt ?></td>
                        </tr>
                        <tr>
                            <td class="text-secondary"><?= $patient->getAttributeLabel('phone') ?></td>
                            <td><?= $patient->phoneNumber ?><button class="mdc-button salamat-color ml-2" type="button" data-toggle="modal" data-target="#update-patient-phone"><?= Yii::t('general', 'Update') ?></button></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="card raised-card has-loading">
            <div class="card-body">
                <h6 class="mb-3"><?= Yii::t('clinic', 'Appointment') ?></h6>
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <?= $form->field($model, 'doctor_branch')->dropdownList($doctors, [
                            'class' => 'form-control bootstrap-select',
                            'data-live-search' => 'true',
                            'disabled' => !$model->isNewRecord,
                        ]) ?>
                    </div>
                    <div class="col-lg-6 col-md-8">
                        <?= $form->field($model, 'service_id')->dropdownList($services, [
                            'class' => 'form-control bootstrap-select',
                            'data-live-search' => 'true',
                            'options' => $servicesOptions,
                            'disabled' => !$model->isNewRecord,
                        ]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <?= $form->field($model, 'date')->textInput([
                            'autocomplete' => 'off',
                            'class' => 'form-control bootstrap-datepicker',
                            'data-date-start-date' => date('Y-m-d'),
                        ]) ?>
                    </div>
                </div>
                <?php if ($model->isNewRecord) {?>
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <?= $form->field($model, 'status')->radioList([
                            $model::STATUS_PENDING => Yii::t('general', 'Pending'),
                            $model::STATUS_WALK_IN => Yii::t('general', 'Walk in'),
                        ]) ?>
                    </div>
                </div>
                <?php }?>
                <div class="row">
                    <div class="col">
                        <?= $form->field($model, 'time')->radioList([]) ?>
                    </div>
                </div>

                <div class="mdc-button-group direction-reverse">
                    <?= Html::button(Html::tag('div', 'refresh', ['class' => 'icon material-icon']).Yii::t('general', 'Refresh'), [
                        'class' => 'mdc-button salamat-color',
                        'onclick' => 'reloadAppointmentForm();',
                    ]) ?>
                </div>
            </div>

            <div class="loading-block">
                <div class="mdc-progress-track salamat-color">
                    <div class="indicator indeterminate"></div>
                </div>
            </div>
        </div>

        <div class="mdc-fab">
            <?= Html::submitButton(Html::tag('div', 'save', ['class' => 'icon material-icon']).Yii::t('general', 'Save'), [
                'class' => 'mdc-fab-button extended bg-salamat-color',
            ]) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

<?php if (!$patient->isNewRecord) { ?>
<?php
if (empty($patient->phone_line)) {
    $patient->phone_line = '973';
}
?>
<div class="modal fade" id="update-patient-phone" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['update-patient-phone', 'id' => $patient->id],
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('patient', 'Update contact number') ?></div>
            </div>
            <div class="modal-body">
                <?= $form->field($patient, 'phone_line')->dropdownList($country::phoneLines(), [
                    'class' => 'form-control bootstrap-select',
                    'data-live-search' => 'true',
                ]) ?>
                <?= $form->field($patient, 'phone')->textInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php } ?>