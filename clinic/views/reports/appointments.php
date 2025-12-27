<?php

use yii\helpers\Html;
use yii\helpers\Json;
use clinic\models\Branch;
use clinic\models\Appointment;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('clinic', 'Appointments');
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

$chartLabels = [];
$chartData = new \stdClass();
$chartData->data = [];
$chartData->backgroundColor = [
    0 => 'rgba(25, 118, 210, 0.7)',
    1 => 'rgba(0, 191, 165, 0.7)',
    2 => 'rgba(255, 152, 0, 0.7)',
    3 => 'rgba(255, 235, 59, 0.7)',
    4 => 'rgba(120, 144, 156, 0.7)',
    5 => 'rgba(229, 57, 53, 0.7)',
    6 => 'rgba(158, 157, 36, 0.7)',
    7 => 'rgba(25, 118, 210, 0.7)',
];
$chartData->borderColor = [
    0 => 'rgba(25, 118, 210, 1)',
    1 => 'rgba(0, 191, 165, 1)',
    2 => 'rgba(255, 152, 0, 1)',
    3 => 'rgba(255, 235, 59, 1)',
    4 => 'rgba(120, 144, 156, 1)',
    5 => 'rgba(229, 57, 53, 1)',
    6 => 'rgba(158, 157, 36, 1)',
    7 => 'rgba(25, 118, 210, 1)',
];

foreach (Appointment::statusList() as $status => $label) {
    $chartLabels[] = $label;
    $chartData->data[] = $data[$status];
}

$chartLabels = Json::encode($chartLabels);
$chartData = Json::encode($chartData);
$chartTotal = $data['total'];

$script = <<< JS
    Chart.defaults.global.defaultFontColor = '$fontColor';

    var appointments = $('#appointments-chart');
    var appointmentsChart = new Chart(appointments, {
        type: 'doughnut',
        data: {
            labels: $chartLabels,
            datasets: [$chartData]
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
                        <div class="col-lg-3 col-md-4">
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
                        <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                            <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                            <h5 class="text-hint my-3"><?= Yii::t('clinic', 'Total appointments: {total}', ['total' => $data['total']]) ?></h5>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-8 col-md-7">
                        <div class="card-body">
                            <?php if (!empty($search->branch_id)) { ?>
                            <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?></h4>
                            <?php } ?>
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By status') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <?php foreach (Appointment::statusList() as $status => $label) { ?>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= $label ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data[$status] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php } ?>

                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By doctors') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <?php foreach ($data['doctors'] as $doctor) { ?>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= $doctor['name'] ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $doctor['total'] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php } ?>
                        
                        <?php if (empty($search->branch_id)) { ?>
                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'By branches') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <?php foreach ($data['branches'] as $branch) { ?>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= $branch['name'] ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $branch['total'] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php } ?>
                        <?php } ?>
                        
                        <div class="card-body">
                            <div class="mdt-h6 salamat-color"><?= Yii::t('clinic', 'Total appointments: {total}', ['total' => $data['total']]) ?></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col py-4">
                        <canvas id="appointments-chart" style="width: 256px; height: 256px;"></canvas>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>