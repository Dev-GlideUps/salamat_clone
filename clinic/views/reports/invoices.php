<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\GridView;
use clinic\models\Branch;
use common\widgets\ActiveForm;
use clinic\models\InvoicePayment;

/* @var $this yii\web\View */

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

$script = <<<JS
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

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <ul class="nav nav-tabs" id="doctor-profile-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#branch-info" role="tab"
                            aria-selected="true"><?= Yii::t('clinic', 'Doctor information') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?= \yii\helpers\Url::to(['/finance/invoices/pdf-view']) ?>"><?= Yii::t('general', 'Patient information') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="card-body tab-pane fade active show" id="branch-info" role="tabpanel">
                        <div class="container-custom">
                            <div class="row">
                                <div class="col">
                                    <div class="media mt-1 mb-3 salamat-color">
                                        <div class="media-icon mr-3">
                                            <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_bar_1.svg')) ?>
                                        </div>
                                        <div class="media-body">
                                            <h5><?= Html::encode($this->title) ?></h5>
                                        </div>
                                    </div>

                                    <div class="card raised-card">
                                        <?php $form = ActiveForm::begin([
                                            'method' => 'get',
                                            'options' => ['class' => 'card-body'],
                                        ]); ?>
                                        <div class="row">
                                            <?php if (count($branches) > 1) { ?>
                                                <div class="col-lg-4 col-md-4">
                                                    <?= $form->field($search, 'branch_id')->dropdownList($branches, [
                                                        'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                                                        'class' => 'form-control bootstrap-select',
                                                        // 'data-live-search' => 'true',
                                                    ]) ?>
                                                </div>
                                            <?php } ?>
                                            <!-- <div class="col-lg-3 col-md-4">
                                                <?= $form->field($search, 'starting_date')->textInput([
                                                    'autocomplete' => 'off',
                                                    'class' => 'form-control bootstrap-datepicker',
                                                    'data-date-end-date' => date('Y-m-d'),
                                                ]) ?>
                                            </div>
                                            <div class="col-lg-3 col-md-4">
                                                <?= $form->field($search, 'ending_date')->textInput([
                                                    'autocomplete' => 'off',
                                                    'class' => 'form-control bootstrap-datepicker',
                                                    'data-date-end-date' => date('Y-m-d'),
                                                ])->hint(Yii::t('general', '* Inclusive')) ?>
                                            </div> -->

                                            <div class="col-lg-3 col-md-4">
                                                <?= $form->field($search, 'starting_date')->textInput([
                                                    'autocomplete' => 'off',
                                                    'class' => 'form-control bootstrap-datepicker',
                                                    'data-date-end-date' => date('Y-m-d')
                                                    // 'class' => 'form-control bootstrap-datetimepicker',
                                                    // 'data-date-end-date' => date('Y-m-d'),
                                                    // 'type' => 'datetime-local', // This will allow both date and time selection
                                                ]) ?>
                                            </div>

                                            <div class="col-lg-3 col-md-4">
                                                <?= $form->field($search, 'ending_date')->textInput([
                                                    'autocomplete' => 'off',
                                                    'class' => 'form-control bootstrap-datepicker',
                                                    'data-date-end-date' => date('Y-m-d')
                                                    // 'class' => 'form-control bootstrap-datetimepicker',
                                                    // 'data-date-end-date' => date('Y-m-d'),
                                                    // 'type' => 'datetime-local', // This will allow both date and time selection
                                                ])->hint(Yii::t('general', '* Inclusive')) ?>
                                            </div>

                                            <div class="col-lg-3 col-md-4">

                                                <?= Html::a('download', "javascript:void(0)", [
                                                    'class' => 'material-icon mr-3 ',
                                                    'data-toggle' => "modal",
                                                    'data-target' => "#exampleModal"
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div class="mdc-button-group direction-reverse p-0">
                                            <?= Html::submitButton(Yii::t('general', 'Apply filters'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>

                                        <div class="mdc-divider"></div>

                                        <?php if ($data['total'] == 0) { ?>
                                            <div class="card-body">
                                                <div class="py-5 text-center">
                                                    <div class="empty-state-graphic"
                                                        style="max-width: 15rem; margin: 0 auto;">
                                                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                                        <h5 class="text-hint my-3">
                                                            <?= Yii::t('finance', 'Total amount: {total}', ['total' => $formatter->asDecimal($data['total'], 3)]) ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="row justify-content-center">
                                                <div class="col-xl-9 col-lg-8 col-md-7">
                                                    <div class="mb-2"></div>

                                                    <div class="card-body">
                                                        <?php if (!empty($search->branch_id)) { ?>
                                                            <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?>
                                                            </h4>
                                                        <?php } ?>
                                                        <!-- <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By Patient') ?> <?= Html::a('cloud_download', ["./finance/invoices/pdf-view"], ['class' => 'material-icon mr-3 float-right', 'target' => '_blank']) ?>
                                                        </div> -->
                                                    </div>
                                                    <div class="mdc-divider mb-2"></div>
                                                    <div class="card-body">
                                                        <?php if (!empty($search->branch_id)) { ?>
                                                            <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?>
                                                            </h4>
                                                        <?php } ?>
                                                        <div class="mdt-subtitle-2 text-secondary">
                                                            <?= Yii::t('clinic', 'By doctors') ?></div>
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
                                                                        <div class="mdt-body text-secondary">
                                                                            <?= Yii::t('finance', 'Payed amount') ?></div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <?php foreach ($doctor['payments'] as $method => $amount) { ?>
                                                                            <div class="mdt-body text-secondary mb-2">
                                                                                <?= $formatter->asDecimal($amount, 3) ?> <span
                                                                                    class="text-hint">(<?= InvoicePayment::methodList()[$method] ?>)</span>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="mdc-divider mb-2 mx-n3"></div>
                                                                        <div class="mdt-body">
                                                                            <?= $formatter->asDecimal($doctor['paid'], 3) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mdc-divider my-2"></div>
                                                                <div class="row mx-0">
                                                                    <div class="col-4 pr-0">
                                                                        <div class="mdt-body text-secondary">
                                                                            <?= Yii::t('finance', 'Balance due') ?></div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="mdt-body">
                                                                            <?= $formatter->asDecimal($doctor['balance'], 3) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mdc-divider my-2"></div>
                                                                <div class="row mx-0">
                                                                    <div class="col-4 pr-0">
                                                                        <div class="mdt-body text-secondary">
                                                                            <?= Yii::t('finance', 'VAT') ?></div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="mdt-body">
                                                                            <?= $formatter->asDecimal($doctor['vat'], 3) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mdc-divider my-2"></div>
                                                                <div class="row mx-0">
                                                                    <div class="col-4 pr-0">
                                                                        <div class="mdt-body text-secondary">
                                                                            <?= Yii::t('finance', 'Total amount') ?></div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="mdt-body salamat-color">
                                                                            <?= $formatter->asDecimal($doctor['total'], 3) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mdc-divider my-2"></div>
                                                    <?php } ?>

                                                    <div class="row mt-5 mx-1">
                                                        <div class="col-4 pr-0">
                                                            <div class="mdt-body text-secondary">
                                                                <?= Yii::t('finance', 'Payed amount') ?></div>
                                                        </div>
                                                        <div class="col">
                                                            <?php foreach ($data['payments'] as $method => $amount) { ?>
                                                                <div class="mdt-body text-secondary mb-2">
                                                                    <?= $formatter->asDecimal($amount, 3) ?> <span
                                                                        class="text-hint">(<?= InvoicePayment::methodList()[$method] ?>)</span>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="mdc-divider mb-2 mx-n3"></div>
                                                            <div class="mdt-body">
                                                                <?= $formatter->asDecimal($data['paid'], 3) ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="mdc-divider my-2"></div>
                                                    <div class="row mx-1">
                                                        <div class="col-4 pr-0">
                                                            <div class="mdt-body text-secondary">
                                                                <?= Yii::t('finance', 'Balance due') ?></div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="mdt-body">
                                                                <?= $formatter->asDecimal($data['balance'], 3) ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="mdc-divider my-2"></div>
                                                    <div class="row mx-1">
                                                        <div class="col-4 pr-0">
                                                            <div class="mdt-body text-secondary">
                                                                <?= Yii::t('finance', 'VAT') ?></div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="mdt-body">
                                                                <?= $formatter->asDecimal($data['vat'], 3) ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="mdc-divider my-2"></div>

                                                    <div class="card-body">
                                                        <div class="mdt-h6 salamat-color">
                                                            <?= Yii::t('finance', 'Total amount: {total}', ['total' => $formatter->asDecimal($data['total'], 3)]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col py-4">
                                                    <canvas id="invoices-chart"
                                                        style="width: 256px; height: 256px;"></canvas>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body tab-pane fade" id="branch-services" role="tabpanel">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose Format</h5>

            </div>
            <div class="modal-body">

                <?= Html::a('<i class="material-icons">picture_as_pdf</i>View as PDF', ["pdf-by-doctor?starting_date=$search->starting_date&ending_date=$search->ending_date"], ['class' => ' mr-3 ', 'target' => '_blank']) ?>


                <?= Html::a('<i class="material-icons">description</i>View as Excel', ["excel-by-doctor?starting_date=$search->starting_date&ending_date=$search->ending_date"], ['class' => ' mr-3 ', 'target' => '_blank']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>