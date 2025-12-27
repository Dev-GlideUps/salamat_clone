<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use clinic\models\InvoiceItem;

/* @var $this yii\web\View */
/* @var $model clinic\models\Invoice */
/* @var $form yii\widgets\ActiveForm */

$ajaxUrl = Url::to(['/finance/invoices/invoice-preview']);
$errorHtml = $this->render('_ajax_error', ['message' => ""]);
$script = <<< JS
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

<div class="card raised-card invoice-form">
    <?php $form = ActiveForm::begin(['id' => 'invoice-form']); ?>
    <?= $form->field($model, "branch_id")->hiddenInput() ?>
    <div class="card-body">
        <h6 class="mb-3"><?= Yii::t('patient', 'Patient information') ?></h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td class="text-secondary"><?= Yii::t('general', 'Name') ?></td>
                        <td><?= $appointment->patient->name ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary" style="width: 25%;"><?= $appointment->patient->getAttributeLabel('cpr') ?></td>
                        <td><?= $appointment->patient->cpr ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary" style="width: 25%;"><?= $appointment->patient->getAttributeLabel('phone') ?></td>
                        <td><?= $appointment->patient->phone ?></td>
                    </tr>
                    <tr>
                        <td class="text-secondary" style="width: 25%;"><?= $appointment->patient->getAttributeLabel('address') ?></td>
                        <td><?= $appointment->patient->address ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h6 class="mt-4"><?= Yii::t('patient', 'Invoice details') ?></h6>
        <small class="text-secondary"><?= Yii::t('invoice', 'Note: Leave quantity field empty if not aplicable.') ?></small>
    </div>
    <div class="mdc-divider mb-3"></div>
    <div class="card-body">
        <div class="row">
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
        <?php foreach ($items as $index => $invoiceItem) {
     
      //  die;
         ?>
            <div class="item-row" data-index="<?= $index ?>">
                <div class="row m-0">
                    <div class="col"><?= $form->field($invoiceItem, "[$index]item")->textInput(['autocomplete' => 'off']) ?></div>
                    <div class="col-lg-3 col-md-3"><?= $form->field($invoiceItem, "[$index]qty")->textInput(['autocomplete' => 'off']) ?></div>
                    <div class="col-lg-3 col-md-3"><?= $form->field($invoiceItem, "[$index]amount")->textInput(['autocomplete' => 'off',   'readonly' => !Yii::$app->user->can('Update Price'),   ]) ?></div>
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
                                    <button type="button" class="mdc-list-item delete-item-row">
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
    <?php ActiveForm::end(); ?>
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
