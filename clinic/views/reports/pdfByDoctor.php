<?php 
use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\GridView;
use clinic\models\Branch;
use common\widgets\ActiveForm;
use clinic\models\InvoicePayment;

$this->title = Yii::t('finance', 'invoices');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\assets\ChartsAsset::register($this);

$formatter = Yii::$app->formatter;
$darkTheme = Yii::$app->user->identity->dark_theme;

$branches = Branch::find()->where(['clinic_id' => Yii::$app->user->identity->active_clinic])->select('name')->indexBy('id')->column();

$fontColor = "#808080";
if ($darkTheme) {
    $fontColor = "#D2D2D2";
}

$invoiceData = new \stdClass();
$invoiceData->data = [
    0 => $formatter->asDecimal($data['paid'], 3),
    1 => $formatter->asDecimal($data['balance'], 3),
];
$invoiceData->backgroundColor = [
    0 => 'rgba(158, 157, 36, 0.7)',
    1 => 'rgba(199, 199, 46, 0.7)',
];
$invoiceData->borderColor = [
    0 => 'rgba(158, 157, 36, 1)',
    1 => 'rgba(199, 199, 46, 1)',
];
$invoiceData = Json::encode($invoiceData);

$invoiceLabels = Json::encode([
    0 => Yii::t('finance', 'Paid amount'),
    1 => Yii::t('finance', 'Balance due'),
]);

$script = <<< JS
    Chart.defaults.global.defaultFontColor = '$fontColor';
    
    var invoices = $('#invoices-chart');
    var invoiceChart = new Chart(invoices, {
        type: 'doughnut',
        data: {
            labels: $invoiceLabels,
            datasets: [$invoiceData]
        },
        options: {
            cutoutPercentage: 80,
            responsive: true,
            tooltips: {
                mode: 'index',
                cornerRadius: 4,
                displayColors: false
            },
            legend: {
                position: 'bottom',
                fullWidth: false
            }
        }
    });
JS;

$this->RegisterJs($script, $this::POS_END);

?>
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-8 col-md-7">
        <div class="mb-2"></div>

        <div class="card-body">
            <?php if (!empty($search->branch_id)) { ?>
                <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?></h4>
            <?php } ?>
            <!-- <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By Patient') ?> <?= Html::a('cloud_download', ["./finance/invoices/pdf-view"], ['class' => 'material-icon mr-3 float-right', 'target' => '_blank']) ?>
                                                        </div> -->
        </div>
        <div class="mdc-divider mb-2"><br></div>
        <div class="card-body">
            <?php if (!empty($search->branch_id)) { ?>
                <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?></h4>
            <?php } ?>
            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By doctors') ?></div>
        </div>
        <div class="mdc-divider mb-2"></div>
        <?php foreach ($data['doctors'] as $doctor) { ?>
            
            <div class="row mx-1">
                <div class="col-4">
                    <div class="mdt-body-2"><?= $doctor['name'] ?></div>
                </div>
                <div class="col p-0">
                
                    <div class="row mx-0">
                        
                        <div class="col-4 pr-0">
                            
                            <div class="mdt-body text-secondary"><?= Yii::t('finance', 'Payed amount') ?></div>
                        </div>
                        <div class="col">
                            <?php foreach ($doctor['payments'] as $method => $amount) { ?>
                                <div class="mdt-body text-secondary mb-2"><?= $formatter->asDecimal($amount, 3) ?> <span class="text-hint">(<?= InvoicePayment::methodList()[$method] ?>)</span></div>
                            <?php } ?>
                            <div class="mdc-divider mb-2 mx-n3"></div>
                            <div class="mdt-body"><?= $formatter->asDecimal($doctor['paid'], 3) ?></div>
                        </div>
                    </div>
                    <div class="mdc-divider my-2"></div>
                    <div class="row mx-0">
                        <div class="col-4 pr-0">
                            <div class="mdt-body text-secondary"><?= Yii::t('finance', 'Balance due') ?></div>
                        </div>
                        <div class="col">
                            <div class="mdt-body"><?= $formatter->asDecimal($doctor['balance'], 3) ?></div>
                        </div>
                    </div>
                    <div class="mdc-divider my-2"></div>
                    <div class="row mx-0">
                        <div class="col-4 pr-0">
                            <div class="mdt-body text-secondary"><?= Yii::t('finance', 'VAT') ?></div>
                        </div>
                        <div class="col">
                            <div class="mdt-body"><?= $formatter->asDecimal($doctor['vat'], 3) ?></div>
                        </div>
                    </div>
                    <div class="mdc-divider my-2"></div>
                    <div class="row mx-0">
                        <div class="col-4 pr-0">
                            <div class="mdt-body text-secondary"><?= Yii::t('finance', 'Total amount') ?></div>
                        </div>
                        <div class="col">
                            <div class="mdt-body salamat-color"><?= $formatter->asDecimal($doctor['total'], 3) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdc-divider my-2"></div>
        <?php } ?>

        <div class="row mt-5 mx-1">
        <br>
        
            <div class="col-4 pr-0">
                <div class="mdt-body text-secondary"><?= Yii::t('finance', 'Payed amount') ?></div>
            </div>
            <div class="col">
                <?php foreach ($data['payments'] as $method => $amount) { ?>
                    <div class="mdt-body text-secondary mb-2"><?= $formatter->asDecimal($amount, 3) ?> <span class="text-hint">(<?= InvoicePayment::methodList()[$method] ?>)</span></div>
                <?php } ?>
                <div class="mdc-divider mb-2 mx-n3"></div>
                <div class="mdt-body"><?= $formatter->asDecimal($data['paid'], 3) ?></div>
            </div>
        </div>
        <div class="mdc-divider my-2"></div>
        <div class="row mx-1">
            <div class="col-4 pr-0">
                <div class="mdt-body text-secondary"><?= Yii::t('finance', 'Balance due') ?></div>
            </div>
            <div class="col">
                <div class="mdt-body"><?= $formatter->asDecimal($data['balance'], 3) ?></div>
            </div>
        </div>
        <div class="mdc-divider my-2"></div>
        <div class="row mx-1">
            <div class="col-4 pr-0">
                <div class="mdt-body text-secondary"><?= Yii::t('finance', 'VAT') ?></div>
            </div>
            <div class="col">
                <div class="mdt-body"><?= $formatter->asDecimal($data['vat'], 3) ?></div>
            </div>
        </div>
        <div class="mdc-divider my-2"></div>
<br>
        <div class="card-body">
            <div class="mdt-h6 salamat-color"><?= Yii::t('finance', 'Total amount: {total}', ['total' => $formatter->asDecimal($data['total'], 3)]) ?></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col py-4">
        <canvas id="invoices-chart" style="width: 256px; height: 256px;"></canvas>
    </div>
</div>
