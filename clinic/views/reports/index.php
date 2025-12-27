<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('general', 'Reports');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = $this->title;
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
        </div>
    </div>
    <div class="row align-items-center justify-content-center report-links">
        <div class="col-md-4 col-sm-6">
            <a href="<?= Url::to(['appointments']) ?>" class="card raised-card mb-3 bg-salamat-secondary color-1">
                <div class="graphic">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/isometric_appointment.svg')) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body mdt-h6"><?= Yii::t('clinic', 'Appointments') ?></div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="<?= Url::to(['patients']) ?>" class="card raised-card mb-3 bg-salamat-secondary color-2">
                <div class="graphic">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/isometric_patient.svg')) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body mdt-h6"><?= Yii::t('patient', 'Patients') ?></div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 d-none">
            <a href="<?= Url::to(['diagnoses']) ?>" class="card raised-card mb-3 bg-salamat-secondary color-3">
                <div class="graphic">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/isometric_diagnosis.svg')) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body mdt-h6"><?= Yii::t('patient', 'Diagnoses') ?></div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="<?= Url::to(['invoices']) ?>" class="card raised-card mb-3 bg-salamat-secondary color-4">
                <div class="graphic">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/isometric_invoice.svg')) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body mdt-h6"><?= Yii::t('finance', 'Invoices income') ?></div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="<?= Url::to(['diagnoses']) ?>" class="card raised-card mb-3 bg-salamat-secondary color-5">
                <div class="graphic">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/isometric_diagnosis_2.svg')) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body mdt-h6"><?= Yii::t('patient', 'Diagnoses') ?></div>
            </a>
        </div>
    </div>
</div>
