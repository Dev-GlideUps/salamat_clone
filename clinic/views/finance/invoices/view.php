<?php

use yii\helpers\Html;
use clinic\models\Appointment;

/* @var $this yii\web\View */
/* @var $model clinic\models\Invoice */

$this->title = $model->invoiceID;
$this->params['breadcrumbs'][] = Yii::t('finance', 'Finance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('finance', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;

$completed = 0;
foreach ($model->appointments as $item) {
    if ($item->status == Appointment::STATUS_COMPLETED) {
        $completed++;
    }
}
?>
<div class="container-custom invoice-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Yii::t('finance', 'Invoice')." - {$this->title}" ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <ul class="nav nav-tabs" id="invoice-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#invoice-details" role="tab" aria-selected="true"><?= Yii::t('finance', 'Invoice') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#invoice-appointments" role="tab" aria-selected="false"><?= Yii::t('clinic', 'Appointments') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#invoice-insurance" role="tab" aria-selected="false"><?= Yii::t('insurance', 'Insurance') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="card-body tab-pane fade active show" id="invoice-details" role="tabpanel">
                        <div class="row justify-content-between">
                            <div class="col-lg-5 col-md-6">
                                <h6 class="mt-2"><?= Yii::t('patient', 'Patient') ?></h6>
                                <div class="mdc-list-container">
                                    <div class="mdc-list-item">
                                        <div class="graphic ml-0" style="background-image: url(<?= $model->patient->photoThumb ?>);"></div>
                                        <div class="text mr-0">
                                            <?= $model->patient->name ?>
                                            <div class="secondary"><?= $model->patient->name_alt ?></div>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="mdc-divider mb-3"></div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->patient->getAttributeLabel('phone') ?></p></div>
                                    <div class="col"><p class="mdt-subtitle-2"><?= $model->patient->phone ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->patient->getAttributeLabel('cpr') ?></p></div>
                                    <div class="col"><p class="mdt-subtitle-2"><?= $model->patient->cpr ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->patient->getAttributeLabel('address') ?></p></div>
                                    <div class="col"><p class="mdt-subtitle-2"><?= $formatter->asText((empty($model->patient->address) ? null : $model->patient->address)) ?></p></div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <h6 class="mt-2"><?= "{$model->clinic->name} - {$model->branch->name}" ?></h6>
                                    
                                <div class="mb-3"></div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('invoiceID') ?></p></div>
                                    <div class="col"><p class="mdt-subtitle-2"><?= $model->invoiceID ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('created_at') ?></p></div>
                                    <div class="col"><p class="mdt-subtitle-2"><?= $formatter->asDate($model->created_at, 'long') ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('max_appointments') ?></p></div>
                                    <div class="col">
                                        <p class="mdt-subtitle-2"><?= count($model->appointments) ?> / <?= $model->max_appointments ?> ( <?= $completed ?> <?= Appointment::statusList()[Appointment::STATUS_COMPLETED] ?> )</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-5 mb-4"><?= Yii::t('finance', 'Invoice details') ?></h6>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><span><?= Yii::t('finance', 'Service / Item') ?></span></th>
                                        <th class="text-right" style="width: 12rem;"><span><?= Yii::t('finance', 'Price') ?></span></th>
                                        <th class="text-right" style="width: 6rem;"><span><?= Yii::t('finance', 'Discount') ?></span></th>
                                        <th class="text-right" style="width: 6rem;"><span><?= Yii::t('finance', 'VAT') ?></span></th>
                                        <th class="text-right" style="width: 8rem;"><span><?= Yii::t('finance', 'Total') ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal = 0;
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
                                </tbody>
                            </table>
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
                                <?php if(isset($model->payments[0])) {
?>
<div class="mdc-divider mb-3"></div>
                                <div class="row mx-0">
                                    <div class="col"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('Payment Method') ?></p></div>
                                    
                                    <div class="col"><p class="mdt-subtitle-2 text-right"><?php if($model->payments[0]->payment_method==0): echo "Cash"; elseif($model->payments[0]->payment_method==1): echo "Cheque";elseif($model->payments[0]->payment_method==2): echo "Debit Card";elseif($model->payments[0]->payment_method==3): echo "Credit Card";elseif($model->payments[0]->payment_method==4): echo "Bank Transfer";elseif($model->payments[0]->payment_method==5): echo "Benefit Pay";endif;  ?></p></div>
                                </div>

<?php } ?>
                                
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
                    <div class="tab-pane fade" id="invoice-appointments" role="tabpanel">
                        <h6 class="text-secondary p-3 m-0"><?= Yii::t('finance', 'Invoice appointments') ?></h6>
                        <?php if (empty($model->appointments)) { ?>
                            <div class="card-body text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No appointments!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                        <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><span><?= Yii::t('clinic', 'Appointment date') ?></span></th>
                                    <th><span><?= Yii::t('clinic', 'Appointment time') ?></span></th>
                                    <th><span><?= Yii::t('clinic', 'Appointment status') ?></span></th>
                                    <th class="action-column"><span></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model->appointments as $item) { ?>
                                <tr>
                                    <td><?= $formatter->asDate($item->date, 'full') ?></td>
                                    <td><?= "{$item->time} - {$item->end_time}" ?></td>
                                    <td><span class="badge badge-pill appointment-status appointment-status-<?= $item->status ?>"><?= $item::statusList()[$item->status] ?></span></td>
                                    <td class="action-column text-right">
                                        <div class="action-buttons">
                                            <?= Html::a('list_alt', ['/clinic/appointments/view', 'id' => $item->id], ['class' => 'material-icon mx-2']) ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                        <p class="mdt-subtitle-2 text-right px-4">
                            <span class="text-secondary"><?= $model->getAttributeLabel('max_appointments') ?>:</span>
                            <?= count($model->appointments) ?> / <?= $model->max_appointments ?> ( <?= $completed ?> <?= Appointment::statusList()[Appointment::STATUS_COMPLETED] ?> )
                        </p>
                        <?php } ?>
                    </div>
                    <div class="tab-pane fade" id="invoice-insurance" role="tabpanel">
                        <h6 class="text-secondary p-3 m-0"><?= Yii::t('insurance', 'Insurance') ?></h6>
                        <?php if (!$model->has_insurance) { ?>
                            <div class="card-body text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('finance', 'This invoice is not covered by insurance!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="row px-3">
                                <div class="col-lg-3 col-md-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_seller') ?></p></div>
                                <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary"><?= $model->insuranceSeller->name ?></p></div>
                            </div>
                            <div class="row px-3">
                                <div class="col-lg-3 col-md-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_buyer') ?></p></div>
                                <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary"><?= $model->insurance_buyer ?></p></div>
                            </div>
                            <div class="row px-3">
                                <div class="col-lg-3 col-md-4"><p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('insurance_amount') ?></p></div>
                                <div class="col align-self-center"><p class="mdt-subtitle-2 text-primary">
                                    <?= $model->insurance_amount ?>
                                    <?= $model->insurance_mode == $model::INSURANCE_PERCENT ? '%' : 'BHD' ?>
                                </p></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <?php if ($model->status == $model::STATUS_ACTIVE) { ?>
                <div class="mdc-divider"></div>
                <div class="mdc-button-group direction-reverse p-3">
                    <?= Html::a(Html::tag('div', 'picture_as_pdf', ['class' => 'icon material-icon']).Yii::t('general', 'PDF export'), ['pdf', 'id' => $model->id], [
                        'class' => 'mdc-button salamat-color',
                        'target' => '_blank',
                    ]) ?>

                    <?php if ($model->canAddAppointment) { ?>
                    <?= Html::a(Html::tag('div', 'event', ['class' => 'icon material-icon']).Yii::t('clinic', 'Create appointment'), [
                        '/clinic/appointments/create',
                        'cpr' => $model->patient->cpr,
                        'nationality' => $model->patient->nationality,
                        'invoice_id' => $model->id,
                    ], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php } ?>

                    <?php if ($model->paid > 0) { ?>
                    <?= Html::a(Html::tag('div', 'credit_card', ['class' => 'icon material-icon']).Yii::t('finance', 'Show payments'), [
                        '/finance/payments/index',
                        'InvoicePaymentSearch' => [
                            'invoice_id' => $model->invoiceID,
                        ],
                    ], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php } ?>

                    <?php if ($model->canUpdateInvoice) { ?>
                    <?= Html::button(Html::tag('div', 'close', ['class' => 'icon material-icon']).Yii::t('finance', 'Cancel invoice'), [
                        'class' => 'mdc-button salamat-color',
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#invoice-cancel',
                        ],
                    ]) ?>
                    <?php } ?>
                </div>
                <?php } else { ?>
                <div class="card-body bg-salamat-secondary">
                    <div class="mdt-body text-secondary">This invoice is cancelled</div>
                </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>

<?php if ($model->balance > 0 && $model->status == $model::STATUS_ACTIVE) { ?>
<div class="mdc-fab">
    <?= Html::a(Html::tag('div', 'credit_card', ['class' => 'icon material-icon']).Yii::t('finance', 'Make a payment'), [
        '/finance/payments/create',
        'id' => $model->id,
    ], [
        'class' => 'mdc-fab-button extended bg-salamat-color',
    ]) ?>
</div>
<?php } ?>

<?php if ($model->canUpdateInvoice && $model->status == $model::STATUS_ACTIVE) { ?>
<div class="modal fade" id="invoice-cancel" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('finance', 'Cancel invoice') ?></div>
            </div>
            <div class="modal-body">
                <?= Yii::t('finance', 'This action cannot be undone. payments cannot be added to cancelled invoices. also, cancelled invoices will not have any impact in analytics and reports.') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Abort') ?></button>
                <?= Html::a(Yii::t('finance', 'Cancel invoice'), ['cancel', 'id' => $model->id], [
                    'class' => 'mdc-button salamat-color',
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>