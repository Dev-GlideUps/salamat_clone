<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use clinic\models\PrescriptionItem;

/* @var $this yii\web\View */
/* @var $model clinic\models\Prescription */
/* @var $form yii\widgets\ActiveForm */

$medicineList = [];
$medicineOptions = [];
foreach ($medicines as $item) {
    $medicineList[$item->name] = $item->name;
    $medicineOptions[$item->name] = ['data-forms' => $item->forms];
}

$formsOptions = [];
foreach (PrescriptionItem::formList() as $format => $_) {
    $formsOptions[$format] = ['class' => "format-$format"];
}

$script = <<< JS
    $('#prescription-form').on('afterValidate', function(event, messages, errorAttributes) {
        if (errorAttributes.length > 0) {
            $('#add-new-prescription').modal('hide');
            return false;
        }
        return true;
    });

    $('#prescription-items select.format-list').val('').trigger('change');

    $('#prescription-items').on('show.bs.select', 'select.format-list', function(e) {
        var index = $(this).closest('.item-row').attr('data-index');
        var medicine = '#prescriptionitem-'+index+'-medicine';
        var forms = $(medicine).find('option:selected').attr('data-forms');

        $('.field-prescriptionitem-'+index+'-form .bootstrap-select .dropdown-menu .dropdown-item').each(function () {
            var format = $(this).prop('class').match(/format-(\d+)/)[1];
            if (forms.includes(format)) {
                $(this).parent().removeClass('d-none');
            } else {
                $(this).parent().addClass('d-none');
            }
        });
    });

    $('#prescription-items').on('change', 'select.medicines-list', function(e) {
        var index = $(this).closest('.item-row').attr('data-index');
        $('#prescriptionitem-'+index+'-form').val('').trigger('change');
    });

    $(document).on("beforeSubmit", "#add-medicine form", function(event) {
        $('#add-medicine .modal-content.has-loading > .loading-block').addClass('active');

        $.ajax({
            url: $('#add-medicine form').attr('action'),
            method:  $('#add-medicine form').attr('method'),
            data: $('form').serialize(),
            dataType: 'json',
            success: function(data) {
                // add new medicine to dropdown inputs
                if (data.success) {
                    console.log(data.item);
                    $('select.bootstrap-select.medicines-list').append(data.item).selectpicker('refresh');
                }

                $('#add-medicine .form-result').html(
                    $('<div/>', {'class': 'media p-3 bg-salamat-light rounded-top rounded-bottom'}).append(
                        $('<div/>', {'class': 'material-icon mr-3 text-secondary', 'text': data.success ? 'check_circle_outline' : 'error'})
                    ).append(
                        $('<div/>', {'class': 'media-body'}).append(
                            $('<h5/>', {'class': 'my-0 text-primary', 'text': data.message})
                        ).append(
                            $('<div/>', {'class': 'mdc-button-group direction-reverse pb-0'}).append(
                                $('<button/>', {
                                    'class': 'mdc-button btn-outlined',
                                    'type': 'button',
                                    'onclick': 'closeNewMedForm('+ data.success.toString() +');',
                                    'text': data.button,
                                })
                            )
                        )
                    )
                ).removeClass('d-none');

                $('#add-medicine form').addClass('d-none').trigger('reset');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#add-medicine .form-result').html(
                    $('<div/>', {'class': 'media p-3 bg-salamat-light rounded-top rounded-bottom'}).append(
                        $('<div/>', {'class': 'material-icon mr-3 text-secondary', 'text': 'error'})
                    ).append(
                        $('<div/>', {'class': 'media-body'}).append(
                            $('<h5/>', {'class': 'my-0 text-primary', 'text': errorThrown})
                        ).append(
                            $('<div/>', {'class': 'mdc-button-group direction-reverse pb-0'}).append(
                                $('<button/>', {
                                    'class': 'mdc-button btn-outlined',
                                    'type': 'button',
                                    'onclick': 'closeNewMedForm(false);',
                                    'text': 'Close',
                                })
                            )
                        )
                    )
                ).removeClass('d-none');
            }
        }).done(function() {
            $('#add-medicine .modal-content.has-loading > .loading-block').removeClass('active');
        });

        return false;
    });

    function submitNewMedForm() {
        $('#add-medicine form').trigger('submit');
    }

    function closeNewMedForm(success) {
        if (success == true) {
            $('#add-medicine').modal('hide');
        }

        $('#add-medicine .form-result').addClass('d-none');
        $('#add-medicine form').removeClass('d-none');
    }
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="card raised-card prescription-form">
    <div class="mdc-list-container">
        <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $model->patient_id]) ?>">
            <div class="graphic" style="background-image: url(<?= $model->patient->photoThumb ?>);"></div>
            <div class="text">
                <?= $model->patient->name ?>
                <div class="secondary"><?= $model->patient->name_alt ?></div>
            </div>
        </a>
    </div>
    <div class="mdc-divider"></div>

    <?php $form = ActiveForm::begin(['id' => 'prescription-form']); ?>

    <?php if (count($branches) > 1) { ?>
    <div class="card-body pb-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <?= $form->field($model, 'branch_id')->dropdownList($branches, [
                    'class' => 'form-control bootstrap-select',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <?php } ?>
    
    <div id="prescription-items">
    <?php foreach ($items as $index => $item) { ?>
        <div class="item-row card-body" data-index="<?= $index ?>">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($item, "[$index]medicine")->dropdownList($medicineList, [
                        'class' => 'form-control bootstrap-select medicines-list',
                        'data-live-search' => 'true',
                        'options' => $medicineOptions,
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($item, "[$index]form")->dropdownList($item::formList(), [
                        'class' => 'form-control bootstrap-select format-list',
                        'options' => $formsOptions,
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($item, "[$index]strength")->textInput(['autocomplete' => 'off'])->hint('E.g. 250 mg/5 mL') ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($item, "[$index]frequency")->textInput(['autocomplete' => 'off'])->hint('E.g. 10 mL/8 hours') ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?= $form->field($item, "[$index]duration")->textInput(['autocomplete' => 'off'])->hint('E.g. 5 days') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <?= $form->field($item, "[$index]comment")->textInput(['autocomplete' => 'off']) ?>
                </div>
                <?php if ($index > 0) { ?>
                <div class="col align-self-end">
                    <div class="mdc-button-group direction-reverse pt-0 pb-3">
                        <button type="button" class="mdc-button salamat-color delete-item-row">
                            <div class="icon material-icon">close</div>
                            <?= Yii::t('patient', 'Remove medicine') ?>
                        </button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    </div>

    <div class="mdc-button-group direction-reverse p-3">
        <button type="button" class="mdc-button salamat-color" onclick="add_prescription_item('<?= Yii::t('patient', 'Remove medicine') ?>');">
            <div class="icon material-icon">add</div>
            <?= Yii::t('invoice', 'Add medicine') ?>
        </button>
    </div>
    
    <div class="mdc-fab">
        <?= Html::button(Html::tag('div', 'local_pharmacy', ['class' => 'icon material-icon']).Yii::t('clinic', 'New medicine'), [
            'class' => 'mdc-fab-button extended bg-salamat-color mb-3',
            'data' => [
                'toggle' => 'modal',
                'target' => '#add-medicine',
            ],
        ]) ?>
        <div></div>
        <?= Html::button(Html::tag('div', 'healing', ['class' => 'icon material-icon']).Yii::t('patient', 'Create prescription'), [
            'class' => 'mdc-fab-button extended bg-salamat-color',
            'data' => [
                'toggle' => 'modal',
                'target' => '#add-new-prescription',
            ],
        ]) ?>
    </div>

    <div class="modal fade" id="add-new-prescription" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"><?= Yii::t('finance', 'Create new prescription') ?></div>
                </div>
                <div class="modal-body">
                    <?= Yii::t('finance', 'Prescriptions cannot be updated or changed after creation. please double check prescription information before creating it.') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                    <?= Html::submitButton(Yii::t('general', 'Create'), ['class' => 'mdc-button salamat-color', 'form' => 'prescription-form']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<div class="py-4"></div>

<div class="modal fade" id="add-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content has-loading">
            <?php $form = ActiveForm::begin([
                'action' => ['/clinic/medicines/create'],
                'method' => 'post',
                'options' => ['class' => ''],
            ]); ?>
                <div class="modal-header">
                    <div class="modal-title"><?= Yii::t('clinic', 'New medicine') ?></div>
                </div>
                <div class="modal-body pb-0">
                    <?= $form->field($medModel, 'name')->textInput(['autocomplete' => 'off']) ?>
                    <?= $form->field($medModel, 'formats')->checkboxList($medModel::formList()) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                    <?= Html::button(Yii::t('clinic', 'Add medicine'), ['class' => 'mdc-button salamat-color', 'onclick' => 'submitNewMedForm();']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            <div class="form-result d-none"></div>
            <div class="loading-block">
                <div class="mdc-progress-track salamat-color">
                    <div class="indicator indeterminate"></div>
                </div>
            </div>
        </div>
    </div>
</div>