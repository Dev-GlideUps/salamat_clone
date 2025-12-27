<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use common\models\Country;
use clinic\models\ClinicPatient;

/* @var $this yii\web\View */
/* @var $model clinic\models\Invoice */

$this->title = Yii::t('finance', 'New invoice');
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('finance', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$country = new Country();
$cprError = !empty($patient->errors['cpr']);

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

$ajaxUrl = Url::to(['/finance/invoices/invoice-preview']);
$errorHtml = $this->render('_ajax_error', ['message' => ""]);
$script = <<< JS
$('#invoice-branch_id').on('change', function () {
    var branch = $(this).val();
    $('#branch-services.mdc-sheets-side .branch-services-list').addClass('d-none');
    $('#branch-services.mdc-sheets-side .branch-services-list.branch-' + branch).removeClass('d-none');
});

$('#invoice-form').on('afterValidate', function (event, messages, errorAttributes) {
    if (errorAttributes.length > 0) {
        $('#add-new-invoice').modal('hide');
        $('#invoice-preview').modal('hide');
        return false;
    }
    return true;
});

$('#invoice-preview').on('hidden.bs.modal', function () {
    $('#invoice-preview .modal-content > .mdc-progress-track').removeClass('d-none');
    $('#invoice-preview .modal-body').html('');
});

$('#invoice-preview').on('show.bs.modal', function (event) {
    var loading = $('#invoice-preview .modal-content > .mdc-progress-track');
    var content = $('#invoice-preview .modal-body');

    $.ajax({
        url: '$ajaxUrl',
        type: 'post',
        data: $('#invoice-form').serialize(),
        success: function (data) {
            $(content).html(data);
            nano_scoller_init('#invoice-preview .modal-body > .nano');
            $(loading).addClass('d-none');
        },
        error: function (data) {
            console.log(data.responseText);
            $(content).html(`$errorHtml`);
            $(loading).addClass('d-none');
        }
    });
});

$('#invoice-has_insurance').on('change', function (event) {
    if ($(this).prop('checked')) {
        $('#insurance-form').collapse('show');
    } else {
        $('#insurance-form').collapse('hide');
    }
});
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="invoice-create">
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
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <div class="card raised-card">
                                <div class="form-label-group form-group">
                                    <input type="text" id="patient-filter-cpr" class="form-control " placeholder="10%" value='10%' disabled >
                                    <label for="patient-cpr"><?= Yii::t('patient', 'VAT') ?></label>
                                    <div class="invalid-feedback">0.10</div>
                                </div>     
                        </div>
                    </div>
                </div>

                <p class="text-secondary salamat-dark my-4"><?= $patient->isNewRecord ? Yii::t('patient', "Couldn't find patient. please check the CPR and Nationality again or create a new patient record.") : '' ?></p>

                <?php $form = ActiveForm::begin(['id' => 'invoice-form']); ?>
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

                    <div class="card raised-card">
                        <div class="card-body">
                            <h6><?= Yii::t('patient', 'Invoice details') ?></h6>
                            <small class="text-secondary"><?= Yii::t('invoice', 'Note: Leave quantity field empty if not aplicable.') ?></small>
                        </div>
                        <div class="mdc-divider mb-3"></div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (count($branches) > 1) { ?>
                                <div class="col-lg-4 col-md-6">
                                    <?= $form->field($model, 'branch_id')->dropdownList($branches, [
                                        'class' => 'form-control bootstrap-select',
                                    ]) ?>
                                </div>
                                <?php } else { ?>
                                <div class="d-none">
                                    <?= $form->field($model, "branch_id")->hiddenInput() ?>
                                </div>
                                <?php } ?>
                                <div class="col-lg-3 col-md-4">
                                    <?= $form->field($model, "max_appointments")->textInput(['autocomplete' => 'off']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="mdc-divider"></div>
                        <div class="px-3 pt-3">
                            <?= $form->field($model, "has_insurance")->checkbox() ?>
                        </div>
                        <div id="insurance-form" class="collapse">
                            <div class="px-3">
                                <div class="row">
                                    <div class="col-lg-4 col-md-5">
                                        <?= $form->field($model, "insurance_seller")->dropdownList($insuranceCompanies, [
                                            'class' => 'form-control bootstrap-select',
                                            'data-live-search' => 'true',
                                        ]) ?>
                                    </div>
                                    <div class="col-lg-4 col-md-5">
                                        <?= $form->field($model, "insurance_buyer")->textInput() ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <?= $form->field($model, "insurance_amount")->textInput(['autocomplete' => 'off']) ?>
                                    </div>
                                    <div class="col-auto pt-1">
                                        <?= $form->field($model, "insurance_mode")->radioList([
                                            $model::INSURANCE_PERCENT => '%',
                                            $model::INSURANCE_FIXED => 'BHD',
                                        ])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mdc-divider"></div>
                        <div id="invoice-items" class="mb-3">
                            <?php foreach ($items as $index => $invoiceItem) { ?>
                                <div class="item-row" data-index="<?= $index ?>">
                                    <div class="row m-0">
                                        <div class="col"><?= $form->field($invoiceItem, "[$index]item")->textInput(['autocomplete' => 'off']) ?></div>
                                        <div class="col-lg-3 col-md-3"><?= $form->field($invoiceItem, "[$index]qty")->textInput(['autocomplete' => 'off']) ?></div>
                                        <div class="col-lg-3 col-md-3"><?= $form->field($invoiceItem, "[$index]amount")->textInput(['autocomplete' => 'off']) ?></div>
                                        <div class="col-sm-auto py-3"><?= $form->field($invoiceItem, "[$index]vat")->switch() ?></div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-lg-3 col-md-3">
                                            <?= $form->field($invoiceItem, "[$index]discount_value")->textInput(['autocomplete' => 'off']) ?>
                                        </div>
                                        <div class="col pt-1">
                                            <?= $form->field($invoiceItem, "[$index]discount_unit")->radioList([
                                                'percent' => '%',
                                                'fixed' => 'BHD',
                                            ])->label(false) ?>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="dropup row-<?= $index ?>" data-delete="<?= Yii::t('finance', 'Remove invoice item') ?>">
                                                <button class="material-icon" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">more_vert</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <div class="mdc-list-group">
                                                        <?php if ($index > 0) { ?>
                                                        <button type="button" class="mdc-list-item delete-item-row"">
                                                            <div class="icon material-icon">close</div>
                                                            <div class="text"><?= Yii::t('finance', 'Remove invoice item') ?></div>
                                                        </button>
                                                        <div class="mdc-divider my-2"></div>
                                                        <?php } ?>
                                                        <button type="button" class="mdc-list-item apply-discount-to-all" data-name="InvoiceItem[<?= $index ?>]">
                                                            <div class="icon material-icon">content_copy</div>
                                                            <div class="text"><?= Yii::t('general', 'Apply Discount to All') ?></div>
                                                        </button>
                                                        <button type="button" class="mdc-list-item open-services-list">
                                                            <div class="icon material-icon">medical_services</div>
                                                            <div class="text"><?= Yii::t('clinic', 'Select service') ?></div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="mdc-button-group direction-reverse pt-0 px-3 pb-3">
                            <button type="button" class="mdc-button salamat-color" onclick="add_invoice_item();">
                                <div class="icon material-icon">add</div>
                                <?= Yii::t('invoice', 'New item') ?>
                            </button>
                        </div>

                        <div class="mdc-fab">
                            <?= Html::button(Html::tag('div', 'preview', ['class' => 'icon material-icon']).Yii::t('finance', 'Invoice preview'), [
                                'class' => 'mdc-fab-button extended bg-salamat-color',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#invoice-preview',
                                ],
                            ]) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div id="branch-services" class="mdc-sheets-side fixed">
    <div class="header">
        <div class="title"><?= Yii::t('clinic', 'Branch services') ?></div>
        <div class="close-action">
            <button class="material-icon invoice-item-service" data-title="" data-price="">close</button>
        </div>
    </div>
    <div class="body nano">
        <div class="nano-content">
            <?php foreach ($services as $id => $items) { ?>
            <div class="branch-services-list branch-<?= $id == $model->branch_id ? "$id" : "$id d-none" ?>">
            <?php if (count($items)) { ?>
                <?php foreach ($items as $item) { ?>
                <button type="button" onclick="" class="card btn-block action-card p-0 mb-3 invoice-item-service"
                    style="text-align: start;"
                    data-title="<?= $item->title ?>"
                    data-price="<?= $item->price ?>"
                >
                    <div class="card-body"><?= $item->title ?></div>
                </button>
                <?php } ?>
            <?php } else { ?>
                <p class="text-secondary"><?= Yii::t('clinic', "Couldn't find services for the selected branch") ?></p>
            <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="mdc-sheets-side-scrim"></div>

<div id="invoice-preview" class="modal full-screen-dialog fade" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-salamat-secondary">
                <button type="button" class="material-icon close-button" data-dismiss="modal">close</button>
                <div class="title"><?= Yii::t('finance', 'Invoice preview') ?></div>
            </div>
            <div class="mdc-progress-track salamat-color">
                <div class="indicator indeterminate"></div>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
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