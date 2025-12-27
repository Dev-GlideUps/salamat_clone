<?php

use yii\helpers\Html;
// use clinic\models\Appointment;

$formatter = Yii::$app->formatter;
?>
<div class="nano">
<div class="nano-content">
    <div class="container-custom invoice-view">
        <div class="row justify-content-between">
            <div class="col align-self-center">
                <?php if ($model->has_insurance) { ?>
                    <?php if ($model->insuranceSeller !== null && !empty($model->insurance_amount)) { ?>
                    <div class="bg-salamat-light rounded-top rounded-bottom px-3">
                        <h6 class="pt-3"><?= Yii::t('insurance', 'Insurance') ?></h6>
                            
                        <div class="mb-3"></div>
                        <div class="row">
                            <div class="col-5"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_seller') ?></p></div>
                            <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary"><?= $model->insuranceSeller->name ?></p></div>
                        </div>
                        <div class="row">
                            <div class="col-5"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_buyer') ?></p></div>
                            <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary"><?= $model->insurance_buyer ?></p></div>
                        </div>
                        <div class="row">
                            <div class="col-5"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_amount') ?></p></div>
                            <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary">
                                <?= $model->insurance_amount ?>
                                <?= $model->insurance_mode == $model::INSURANCE_PERCENT ? '%' : 'BHD' ?>
                            </p></div>
                        </div>
                    </div>
                    <?php } else { ?>
                        <div class="bg-salamat-light rounded-top rounded-bottom p-0">
                            <div class="mdc-list-item">
                                <div class="icon material-icon">error</div>
                                <div class="text">
                                    <?= Yii::t('insurance', "Couldn't display insurance information") ?>
                                    <div class="secondary"><?= Yii::t('insurance', "Please make sure insurance fields are correct") ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-lg-5 col-md-6">
                <h6 class="mt-4"><?= "{$model->branch->clinic->name} - {$model->branch->name}" ?></h6>
                    
                <div class="mb-3"></div>
                <div class="row">
                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('invoiceID') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2">N/A</p></div>
                </div>
                <div class="row">
                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('created_at') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2"><?= $formatter->asDate(time(), 'long') ?></p></div>
                </div>
                <div class="row">
                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('max_appointments') ?></p></div>
                    <div class="col">
                        <p class="mdt-subtitle-2">0 / <?= $model->max_appointments ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h6 class="mt-2 mb-4"><?= Yii::t('finance', 'Invoice details') ?></h6>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><span><?= Yii::t('finance', 'Service / Item') ?></span></th>
                                <th class="text-right" style="width: 12rem;"><span><?= Yii::t('finance', 'Price') ?></span></th>
                                <th class="text-right" style="width: 6rem;"><span><?= Yii::t('finance', 'Discount') ?></span></th>
                                <th class="text-right" style="width: 6rem;"><span><?= Yii::t('finance', 'VAT') ?> (10%)</span></th>
                                <th class="text-right" style="width: 8rem;"><span><?= Yii::t('finance', 'Total') ?></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $subtotal = 0;
                            if ($model->invoiceItems !== null) {
                                foreach ($model->invoiceItems as $item) {
                                    $price = $formatter->asDecimal($item['amount'], 3);
                                    $subtotalAmount = $item['qty'] === null ? $item['amount'] : $item['qty'] * $item['amount'];
                                    $discount = 0;
                                    if (!empty($item['discount_unit'])) {
                                        if ($item['discount_unit'] == 'percent') {
                                            $discount = $subtotalAmount * ($item['discount_value'] / 100);
                                        } else {
                                            $discount = $item['discount_value'];
                                        }
                                    }
                                    $vat = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                                    $subtotal += $subtotalAmount;
                                ?>
                                <tr>
                                    <td style="white-space: normal; line-height: 1.25;"><?= $item['item'] ?></td>
                                    <td class="text-right"><?= $item['qty'] === null ? $price : "{$item['qty']} x {$price}" ?></td>
                                    <td class="text-right"><?= $formatter->asDecimal($discount, 3) ?></td>
                                    <td class="text-right"><?= $formatter->asDecimal($vat, 3) ?></td>
                                    <td class="text-right"><?= $formatter->asDecimal(($subtotalAmount - $discount + $vat), 3) ?></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td class="bg-salamat-light p-0" colspan="5">
                                        <div class="mdc-list-item">
                                            <div class="icon material-icon">error</div>
                                            <div class="text">
                                                <?= Yii::t('finance', "Couldn't calculate invoice items") ?>
                                                <div class="secondary"><?= Yii::t('finance', "Please check items for input errors") ?></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-lg-5 col-md-6">
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= Yii::t('finance', 'Subtotal') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($subtotal, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('discount') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($model->discount, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('vat') ?> (10%)</p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($model->vat, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_coverage') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($model->insurance_coverage, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('total') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($model->total, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('paid') ?></p></div>
                    <div class="col"><p class="mdt-subtitle-2 text-right"><?= $formatter->asDecimal($model->paid, 3) ?></p></div>
                </div>
                <div class="mdc-divider mb-3"></div>
                <div class="row mx-0">
                    <div class="col"><h6 class="salamat-color"><?= $model->getAttributeLabel('balance') ?></h6></div>
                    <div class="col"><h6 class="salamat-color text-right"><?= $formatter->asDecimal($model->balance, 3) ?></h6></div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-5"></div>

    <?php if ($model->invoiceItems !== null) { ?>
    <div class="mdc-fab">
        <?= Html::button(Html::tag('div', 'receipt', ['class' => 'icon material-icon']).Yii::t('finance', 'Create invoice'), [
            'class' => 'mdc-fab-button extended bg-salamat-color',
            'data' => [
                'toggle' => 'modal',
                'target' => '#add-new-invoice',
            ],
        ]) ?>
    </div>
    <div class="modal fade" id="add-new-invoice" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"><?= Yii::t('finance', 'Create new invoice') ?></div>
                </div>
                <div class="modal-body">
                    <?= Yii::t('finance', 'Invoices cannot be updated or changed after creation. please double check invoice information before creating it.') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                    <?= Html::submitButton(Yii::t('general', 'Create'), ['class' => 'mdc-button salamat-color', 'form' => 'invoice-form']) ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('body').append($('#add-new-invoice'));
    </script>
    <?php } ?>
</div>
</div>