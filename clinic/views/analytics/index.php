<?php

use yii\helpers\Html;
use yii\helpers\Json;
use clinic\models\InvoicePayment;

/* @var $this yii\web\View */

$this->title = Yii::t('general', 'Analytics');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = $this->title;

\common\assets\ChartsAsset::register($this);

$fontColor = "#808080";
$gridColor = "#B6B6B6";
if ($darkTheme) {
    $fontColor = "#D2D2D2";
    $gridColor = "#646464";
}

$data = $allPayment;
$chartLabels = [];
$chartData = new \stdClass();
$chartData->data = [];
$chartData->backgroundColor = [
    0 => 'rgba(25, 118, 210, 0.7)',
    1 => 'rgba(0, 191, 165, 0.7)',
    2 => 'rgba(255, 152, 0, 0.7)',
    3 => 'rgba(255, 235, 59, 0.7)',
    4 => 'rgba(158, 157, 36, 0.7)',
    5 => 'rgba(229, 57, 53, 0.7)',
    6 => 'rgba(158, 157, 36, 0.7)',
    7 => 'rgba(25, 118, 210, 0.7)',
];
$chartData->borderColor = [
    0 => 'rgba(25, 118, 210, 1)',
    1 => 'rgba(0, 191, 165, 1)',
    2 => 'rgba(255, 152, 0, 1)',
    3 => 'rgba(255, 235, 59, 1)',
    4 => 'rgba(158, 157, 36, 0.7)',
    5 => 'rgba(229, 57, 53, 1)',
    6 => 'rgba(158, 157, 36, 1)',
    7 => 'rgba(25, 118, 210, 1)',
];

foreach (InvoicePayment::methodList() as $status => $label) {
    $chartLabels[] = $label;
    $chartData->data[] = $data[$status];
}

$chartData->data[] = $data['total'];
$chartLabels[] = "Total";

$chartLabels = Json::encode($chartLabels);
$chartData = Json::encode($chartData);
$chartTotal = $data['total'];

$script = <<< JS
    Chart.defaults.global.defaultFontColor = '$fontColor';
    
    var appointments = $('#appointments-chart');

    var appointmentsChart = new Chart(appointments, {
        type: 'line',
        data: {
            labels: $appointmentsLabels,
            datasets: $appointmentsData
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: true,
                cornerRadius: 4,
                displayColors: false
            },
            legend: {
                position: 'bottom',
                align: 'start',
                fullWidth: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '$gridColor',
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '$gridColor'
                    }
                }]
            }
        }
    });
    
    var patients = $('#patients-chart');
    var patientsChart = new Chart(patients, {
        type: 'line',
        data: {
            labels: $patientsLabels,
            datasets: $patientsData
        },
        options: {
            responsive: true,
            tooltips: {
                cornerRadius: 4,
                displayColors: false
            },
            legend: {
                position: 'bottom',
                align: 'start',
                fullWidth: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '$gridColor',
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '$gridColor'
                    }
                }]
            }
        }
    });
    
    var diagnoses = $('#diagnoses-chart');
    var diagnosesChart = new Chart(diagnoses, {
        type: 'bar',
        data: {
            labels: $diagnosesLabels,
            datasets: $diagnosesData
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: true,
                cornerRadius: 4,
                displayColors: false
            },
            legend: {
                position: 'bottom',
                align: 'start',
                fullWidth: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '$gridColor',
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '$gridColor'
                    }
                }]
            }
        }
    });


    var payments = $('#payment-chart');
    var paymentsChart = new Chart(payments, {
        type: 'pie',
        data: {
           
            datasets: [$chartData],
            labels: $chartLabels
        },

        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                cornerRadius: 4,
                
            },
            legend: {
                position: 'right',
                fullWidth: false
            },
            
        }
    });


    
    var invoices = $('#invoices-chart');
    var invoicesChart = new Chart(invoices, {
        type: 'bar',
        data: {
            labels: $invoicesLabels,
            datasets: $invoicesData
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
                intersect: true,
                cornerRadius: 4,
                displayColors: false
            },
            legend: {
                position: 'bottom',
                align: 'start',
                fullWidth: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '$gridColor',
                        drawOnChartArea: false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '$gridColor'
                    }
                }]
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
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_line_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <div class="card raised-card mb-4">
                <div class="card-body">
                    <p class="mdt-body"><?= Yii::t('clinic', 'Appointments') ?></p>
                    <canvas id="appointments-chart" style="width: 320px; height: 180px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card raised-card mb-4">
                <div class="card-body">
                    <p class="mdt-body"><?= Yii::t('patient', 'New patients') ?></p>
                    <canvas id="patients-chart" style="width: 320px; height: 180px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <div class="card raised-card mb-4">
                <div class="card-body">
                    <p class="mdt-body"><?= Yii::t('patient', 'Diagnoses, perscriptions, sick leaves') ?></p>
                    <canvas id="diagnoses-chart" style="width: 320px; height: 180px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card raised-card mb-4">
                <div class="card-body">
                    <p class="mdt-body"><?= Yii::t('finance', 'Invoices income') ?></p>
                    <canvas id="invoices-chart" style="width: 320px; height: 180px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12">
            <div class="card raised-card mb-4">
                <div class="card-body">
                    <p class="mdt-body"><?= Yii::t('finance', 'Payment Methods') ?></p>
                    <!-- <canvas id="invoices-chart2" style="width: 320px; height: 180px;"></canvas> -->
                    <canvas id="payment-chart" style="width: 150px; height: 150px;"></canvas>

                </div>
            </div>
        </div>
    </div>
</div>
