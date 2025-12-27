<?php

use yii\helpers\Html;
use yii\helpers\Json;
use clinic\models\Patient;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\assets\ChartsAsset::register($this);

$formatter = Yii::$app->formatter;
$darkTheme = Yii::$app->user->identity->dark_theme;

$fontColor = "#808080";
if ($darkTheme) {
    $fontColor = "#D2D2D2";
}

$genderData = new \stdClass();
$genderData->data = [
    0 => $data['male'],
    1 => $data['female'],
    2 => $data['unknown'],
];
$genderData->backgroundColor = [
    0 => 'rgba(158, 157, 36, 0.7)',
    1 => 'rgba(158, 157, 36, 0.7)',
    2 => 'rgba(168, 168, 168, 0.7)',
];
$genderData->borderColor = [
    0 => 'rgba(158, 157, 36, 1)',
    1 => 'rgba(158, 157, 36, 1)',
    2 => 'rgba(168, 168, 168, 1)',
];
$genderData = Json::encode($genderData);

$genderLabels = Json::encode([
    0 => Yii::t('general', 'Male'),
    1 => Yii::t('general', 'Female'),
    2 => Yii::t('general', 'Not specified'),
]);

$ageData = new \stdClass();
$ageData->data = $data['ages'];
$ageData->backgroundColor = [
    0 => 'rgba(158, 157, 36, 0.7)',
    1 => 'rgba(158, 157, 36, 0.7)',
    2 => 'rgba(158, 157, 36, 0.7)',
    3 => 'rgba(158, 157, 36, 0.7)',
    4 => 'rgba(158, 157, 36, 0.7)',
    5 => 'rgba(168, 168, 168, 0.7)',
];
$ageData->borderColor = [
    0 => 'rgba(158, 157, 36, 1)',
    1 => 'rgba(158, 157, 36, 1)',
    2 => 'rgba(158, 157, 36, 1)',
    3 => 'rgba(158, 157, 36, 1)',
    4 => 'rgba(158, 157, 36, 1)',
    5 => 'rgba(168, 168, 168, 1)',
];
$ageData = Json::encode($ageData);

$ageLabels = Json::encode([
    0 => Yii::t('general', '{num} years', ['num' => '0 - 4']),
    1 => Yii::t('general', '{num} years', ['num' => '5 - 14']),
    2 => Yii::t('general', '{num} years', ['num' => '15 - 24']),
    3 => Yii::t('general', '{num} years', ['num' => '25 - 64']),
    4 => Yii::t('general', '{num} years', ['num' => '65+']),
    5 => Yii::t('general', 'Not specified'),
]);

$chartTotal = $data['total'];

$script = <<< JS
    Chart.defaults.global.defaultFontColor = '$fontColor';

    var genders = $('#gender-chart');
    var gendersChart = new Chart(genders, {
        type: 'doughnut',
        data: {
            labels: $genderLabels,
            datasets: [$genderData]
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
    
    var ages = $('#age-chart');
    var agesChart = new Chart(ages, {
        type: 'doughnut',
        data: {
            labels: $ageLabels,
            datasets: [$ageData]
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
                            <h5 class="text-hint my-3"><?= Yii::t('patient', 'Total patients: {total}', ['total' => $data['total']]) ?></h5>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-8 col-md-7">
                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', 'By gender') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', 'Male') ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['male'] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', 'Female') ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['female'] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', 'Not specified') ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['unknown'] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        
                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', 'By age') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', '{num} years', ['num' => '0 - 4']) ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][0] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', '{num} years', ['num' => '5 - 14']) ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][1] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', '{num} years', ['num' => '15 - 24']) ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][2] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', '{num} years', ['num' => '25 - 64']) ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][3] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', '{num} years', ['num' => '65+']) ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][4] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= Yii::t('general', 'Not specified') ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['ages'][5] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        
                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('patient', 'By marital status') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <?php foreach (Patient::statusList() as $status => $label) { ?>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= $label ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $data['status'][$status] ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php } ?>
                        
                        <div class="card-body">
                            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('patient', 'By blood type') ?></div>
                        </div>
                        <div class="mdc-divider mb-2"></div>
                        <?php foreach ($data['blood'] as $type => $total) { ?>
                        <div class="row mx-1">
                            <div class="col-4 pr-0"><div class="mdt-body text-secondary"><?= $type ?></div></div>
                            <div class="col"><div class="mdt-body"><?= $total ?></div></div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php } ?>
                        
                        <div class="card-body">
                            <div class="mdt-h6 salamat-color"><?= Yii::t('patient', 'Total patients: {total}', ['total' => $data['total']]) ?></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col py-4">
                        <canvas id="gender-chart" style="width: 256px; height: 256px;"></canvas>
                        <div class="mdc-divider my-3"></div>
                        <canvas id="age-chart" style="width: 256px; height: 256px;"></canvas>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>