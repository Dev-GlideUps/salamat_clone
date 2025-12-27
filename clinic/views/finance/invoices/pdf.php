<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\hr\Salary */
$this->context->layout = 'plain';
$this->title = Yii::t('finance', 'Invoice') . " - {$model->invoiceID}";

$formatter = Yii::$app->formatter;
// $photo = empty($model->staff->photoPath) ? Yii::getAlias('@frontend/web/img/person_light.jpg') : $model->staff->photoPath;

$apps = [];
foreach ($model->appointments as $item) {
    if (!isset($apps[$item::statusList()[$item->status]])) {
        $apps[$item::statusList()[$item->status]] = 0;
    }
    $apps[$item::statusList()[$item->status]]++;
}
?>

<body id="root">
    <div class="row">
        <div class="col-3">
            <?php
            if (!empty(Yii::$app->user->identity->activeClinic->logo)) {
                $file = Yii::$app->user->identity->activeClinic->logo;
                $image = Yii::getAlias("@clinic/documents/clinics/logo/$file");
                $imageData = base64_encode(file_get_contents($image));
                echo Html::img('data: ' . mime_content_type($image) . ';base64,' . $imageData, ['style' => 'max-height: 3cm; max-width: 4cm; margin-bottom: 0.5cm;']);
            } else {
                ?>
                <div style="padding-right: 8pt;">
                    <h5><?= $model->branch->clinic->name ?></h5>
                </div>
            <?php } ?>

            <div class="mdt-subtitle-2 text-secondary">Billed to</div>
            <div class="mdt-body"><?= $model->patient->name ?></div>
            <div class="mdt-body"><?= $model->patient->phone ?></div>
            <p><?= $model->patient->address ?></p>
        </div>
        <div class="col-5 ">
            <p style="visibility:hidden">hello world</p>
            <p class="mdt-subtitle-2 text-secondary" style="margin-top:10px;visibility:hidden;">
                <?= !empty($model->branch->clinic->vat_account) ? "Vat Account Number" : '' ?></p>
            <p style="visibility:hidden">
                <?= !empty($model->branch->clinic->vat_account) ? $model->branch->clinic->vat_account : '' ?></p>
            <p class="mdt-subtitle-2 text-secondary" style="margin-top:0px;">
                <?= !empty($model->branch->clinic->tax_account) ? "Taxed Invoice" : '' ?></p>

            <p class="mdt-subtitle-2 "><?= !empty($model->branch->clinic->tax_account) ? "Tax Number" : '' ?></p>
            <p><?= !empty($model->branch->clinic->tax_account) ? $model->branch->clinic->tax_account : '' ?></p>
            <div>

                <!-- <p><?= $model->branch->clinic->tax_account ?></p> -->
            </div>
        </div>


        <div class="col-4">
            <p class="mdt-subtitle-2 text-secondary"><?= $model->branch->contactNumber ?></p>
            <p class="mdt-subtitle-2 text-secondary"><?= $model->branch->address ?></p>

            <div class="divider" style="margin-bottom: 0.5cm;"></div>

            <div class="mdt-subtitle-2 text-secondary">Invoice number</div>
            <p><?= $model->invoiceID ?></p>
            <div class="mdt-subtitle-2 text-secondary">Date of issue</div>
            <p><?= $formatter->asDate($model->created_at, 'long') ?></p>
        </div>
    </div>
    <div style="padding: 20pt 0;"></div>
    <div class="row">
        <div class="col-4">
            <div class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Service / Item</div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle-2 text-secondary text-right" style="padding: 4pt 0;">Price</div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle-2 text-secondary text-right" style="padding: 4pt 0;">Discount</div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle-2 text-secondary text-right" style="padding: 4pt 0;">VAT (10%)</div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle-2 text-secondary text-right" style="padding: 4pt 0;">Total</div>
        </div>
    </div>
    <div class="divider"></div>

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
        <div class="row">
            <div class="col-4">
                <div class="mdt-subtitle" style="padding: 8pt 0;"><?= $item['item'] ?></div>
            </div>
            <div class="col-2">
                <div class="mdt-subtitle text-right" style="padding: 8pt 0;">
                    <?= $item['qty'] === null ? $price : "{$item['qty']} x {$price}" ?></div>
            </div>
            <div class="col-2">
                <div class="mdt-subtitle text-right" style="padding: 8pt 0;"><?= $formatter->asDecimal($discount, 3) ?>
                </div>
            </div>
            <div class="col-2">
                <div class="mdt-subtitle text-right" style="padding: 8pt 0;"><?= $formatter->asDecimal($vat, 3) ?> </div>
            </div>
            <div class="col-2">
                <div class="mdt-subtitle text-right" style="padding: 8pt 0;">
                    <?= $formatter->asDecimal(($subtotalAmount - $discount + $vat), 3) ?></div>
            </div>
        </div>
        <div class="divider"></div>
    <?php } ?>

    <div class="row">
        <div class="col-7">
            <div class="row">
                <div class="col-6">
                    <p class="mdt-subtitle" style="margin-top: 2cm;">Appointments</p>
                    <div class="divider"></div>
                </div>
            </div>
            <?php foreach ($apps as $status => $count) { ?>
                <div class="row">
                    <div class="col-3">
                        <div class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $status ?></div>
                    </div>
                    <div class="col-3">
                        <div class="mdt-subtitle-2" style="padding: 4pt 0;"><?= $count ?></div>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-6">
                    <div class="divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="mdt-subtitle-2" style="padding: 4pt 0;">Total</div>
                </div>
                <div class="col-3">
                    <div class="mdt-subtitle-2" style="padding: 4pt 0;"><?= count($model->appointments) ?> /
                        <?= $model->max_appointments ?></div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">Subtotal</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($subtotal, 3) ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">Discount</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($model->discount, 3) ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">VAT (10%)</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($model->vat, 3) ?></div>
                </div>
            </div>
            <?php if(isset($model->payments[0])) {
?>
 <div class="row ">
                <div class="col-6">
                    <p class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('Payment Method') ?></p>
                </div>
                <div class="col-6">
                    <p class="mdt-subtitle-2 text-right">
                        <?php if ($model->payments[0]->payment_method == 0): echo "Cash"; elseif ($model->payments[0]->payment_method == 1): echo "Cheque"; elseif ($model->payments[0]->payment_method == 2): echo "Debit Card"; elseif ($model->payments[0]->payment_method == 3):
                            echo "Credit Card";elseif($model->payments[0]->payment_method==4): echo "Bank Transfer";elseif($model->payments[0]->payment_method==5): echo "Benefit Pay"; endif; ?>
                    </p>
                </div>
            </div>
<?php

            }?>
           
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">Insurance</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($model->insurance_coverage, 3) ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">Total amount</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($model->total, 3) ?></div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-secondary" style="padding: 8pt 0;">Amount paid</div>
                </div>
                <div class="col-6">
                    <div class="mdt-subtitle-2 text-right" style="padding: 8pt 0;">
                        <?= $formatter->asDecimal($model->paid, 3) ?></div>
                </div>
            </div>

            <div class="divider" style="margin-bottom: 8pt;"></div>
            <div class="row">
                <div class="col-6">
                    <p class="salamat-color">Balance due</p>
                </div>
                <div class="col-6">
                    <p class="salamat-color text-right"><?= $formatter->asDecimal($model->balance, 3) ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($model->clinic->invoice_terms)) { ?>
        <div class="invoice-terms" style="margin-top: 1cm;">
            <p class="mdt-caption text-secondary"><?= nl2br($model->clinic->invoice_terms) ?></p>
        </div>
    <?php } ?>
</body>