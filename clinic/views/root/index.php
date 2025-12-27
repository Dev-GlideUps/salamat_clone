<?php

/* @var $this yii\web\View */

$this->title = Yii::t('general', 'Dashboard');

$formatter = Yii::$app->formatter;
?>

<div class="dashboard-banner bg-salamat-color mb-5" style="margin-top: -6rem;">
    <div class="container-custom">
        <div class="row justify-content-center align-items-end py-4">
            <div class="col-xl-9 col-lg-8 col-md-7 col-auto">
                <h3 class="d-md-block d-none"><?= Yii::$app->user->identity->activeClinic->name ?></h3>
                <h4 class="d-md-none d-block text-center"><?= Yii::$app->user->identity->activeClinic->name ?></h4>
            </div>
            <div class="col-md col-sm-10 col-12 text-center">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/system.svg')) ?>
            </div>
        </div>
    </div>
</div>

<div class="container-custom">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-4 col-md-6">
            <div class="card raised-card mb-4 dashboard-stat-card">
                <div class="graphics salamat-color"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?></div>
                <div class="card-body pr-1">
                    <p class="mb-2"><?= Yii::t('patient', 'Patients') ?></p>
                    <h4 class="salamat-color"><?= $formatter->asDecimal($totalPatients) ?></h4>
                    <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', '{count} this month', ['count' => $formatter->asDecimal($thisMonthPatients)]) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card raised-card mb-4 dashboard-stat-card">
                <div class="graphics salamat-color"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?></div>
                <div class="card-body pr-1">
                    <p class="mb-2"><?= Yii::t('clinic', 'Appointments') ?></p>
                    <h4 class="salamat-color"><?= $formatter->asDecimal($totalAppointments) ?></h4>
                    <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', '{count} this month', ['count' => $formatter->asDecimal($thisMonthAppointments)]) ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card raised-card mb-4 dashboard-stat-card">
                <div class="graphics salamat-color"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/two_bottles.svg')) ?></div>
                <div class="card-body pr-1">
                    <p class="mb-2"><?= Yii::t('clinic', 'Prescriptions') ?></p>
                    <h4 class="salamat-color"><?= $formatter->asDecimal($totalPrescriptions) ?></h4>
                    <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', '{count} this month', ['count' => $formatter->asDecimal($thisMonthPrescriptions)]) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card raised-card">
        <div class="media p-3 bg-salamat-light rounded-top">
            <div class="material-icon mr-3 text-secondary">system_update_alt</div>
            <div class="media-body">
                <h5 class="mt-0 text-primary">Latest updates <small>January 25th, 2021</small></h5>
                <div class="text-secondary">
                    <ul class="pl-3 mb-0">
                        <li>Added doctor examination notes for patients. A new note can be created by the doctor from patient profile page.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="media p-3">
            <div class="material-icon mr-3 text-secondary">system_update_alt</div>
            <div class="media-body">
                <h5 class="mt-0 text-primary">Update <small>January 22nd, 2021</small></h5>
                <div class="text-secondary">
                    <ul class="pl-3 mb-0">
                        <li>Added the ability to add a new medicine to the list when creating a prescription for the patient.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
