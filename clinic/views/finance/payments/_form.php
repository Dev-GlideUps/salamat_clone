<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoicePayment */
/* @var $form yii\widgets\ActiveForm */

$formatter = Yii::$app->formatter;
?>

<div class="card raised-card invoice-payment-form">
    <?php $form = ActiveForm::begin(['id' => 'payment-form']); ?>
    <div class="card-body">
        <h6 class="salamat-color"><?= $invoice->getAttributeLabel('balance') ?>: <?= $formatter->asDecimal($invoice->balance, 3) ?></h6>
        <?= Html::a(Yii::t('finance', 'Show invoice'), ['/finance/invoices/view', 'id' => $invoice->id], ['class' => 'mdt-subtitle-2']) ?>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <?= $form->field($model, 'amount_paid')->textInput() ?>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <?= $form->field($model, 'payment_method')->dropdownList($model::methodList(), [
                    'class' => 'form-control bootstrap-select',
                    // 'data-live-search' => 'true',
                ]) ?>
            </div>
        </div>

        <div class="mdc-button-group direction-reverse p-0">
            <?= Html::button(Html::tag('div', 'credit_card', ['class' => 'icon material-icon']).Yii::t('finance', 'Save payment'), [
                'class' => 'mdc-button salamat-color',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#add-new-payment',
                ],
            ]) ?>
        </div>

        <div class="modal fade" id="add-new-payment" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title"><?= Yii::t('finance', 'Save payment') ?></div>
                    </div>
                    <div class="modal-body">
                        <?= Yii::t('finance', 'Payments cannot be updated or changed after creation.') ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button salamat-color', 'form' => 'payment-form']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
