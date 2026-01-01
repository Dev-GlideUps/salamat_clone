<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('general', 'Dashboard');

$formatter = Yii::$app->formatter;
$user = Yii::$app->user->identity;
$hour = date('H');
$greeting = $hour < 12 ? Yii::t('general', 'Good morning') : ($hour < 17 ? Yii::t('general', 'Good afternoon') : Yii::t('general', 'Good evening'));
?>

<div class="dashboard-page">
    <!-- Welcome Header -->
    <div class="dashboard-header">
        <div class="container-custom">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="welcome-text">
                        <p class="greeting"><?= $greeting ?></p>
                        <h2 class="user-name"><?= Html::encode($user->name) ?></h2>
                        <p class="clinic-name">
                            <span class="material-icon">business</span>
                            <?= Html::encode($user->activeClinic->name) ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 d-none d-md-block">
                    <div class="header-illustration">
                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/system.svg')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-custom">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card patients">
                <div class="stat-icon">
                    <span class="material-icon">groups</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label"><?= Yii::t('patient', 'Patients') ?></span>
                    <span class="stat-value"><?= $formatter->asDecimal($totalPatients) ?></span>
                    <span class="stat-change positive">
                        <span class="material-icon">trending_up</span>
                        <?= $formatter->asDecimal($thisMonthPatients) ?> <?= Yii::t('general', 'this month') ?>
                    </span>
                </div>
            </div>

            <div class="stat-card appointments">
                <div class="stat-icon">
                    <span class="material-icon">calendar_today</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label"><?= Yii::t('clinic', 'Appointments') ?></span>
                    <span class="stat-value"><?= $formatter->asDecimal($totalAppointments) ?></span>
                    <span class="stat-change positive">
                        <span class="material-icon">trending_up</span>
                        <?= $formatter->asDecimal($thisMonthAppointments) ?> <?= Yii::t('general', 'this month') ?>
                    </span>
                </div>
            </div>

            <div class="stat-card prescriptions">
                <div class="stat-icon">
                    <span class="material-icon">medication</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label"><?= Yii::t('clinic', 'Prescriptions') ?></span>
                    <span class="stat-value"><?= $formatter->asDecimal($totalPrescriptions) ?></span>
                    <span class="stat-change positive">
                        <span class="material-icon">trending_up</span>
                        <?= $formatter->asDecimal($thisMonthPrescriptions) ?> <?= Yii::t('general', 'this month') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section">
            <h5 class="section-title"><?= Yii::t('general', 'Quick Actions') ?></h5>
            <div class="quick-actions-grid">
                <a href="<?= Url::to(['/clinic/patients/create']) ?>" class="quick-action-card">
                    <div class="action-icon add-patient">
                        <span class="material-icon">person_add</span>
                    </div>
                    <span class="action-label"><?= Yii::t('patient', 'New Patient') ?></span>
                </a>
                <a href="<?= Url::to(['/clinic/appointments/create']) ?>" class="quick-action-card">
                    <div class="action-icon add-appointment">
                        <span class="material-icon">event_available</span>
                    </div>
                    <span class="action-label"><?= Yii::t('clinic', 'New Appointment') ?></span>
                </a>
                <a href="<?= Url::to(['/clinic/appointments']) ?>" class="quick-action-card">
                    <div class="action-icon view-schedule">
                        <span class="material-icon">view_agenda</span>
                    </div>
                    <span class="action-label"><?= Yii::t('clinic', 'View Schedule') ?></span>
                </a>
                <a href="<?= Url::to(['/reports']) ?>" class="quick-action-card">
                    <div class="action-icon reports">
                        <span class="material-icon">analytics</span>
                    </div>
                    <span class="action-label"><?= Yii::t('general', 'Reports') ?></span>
                </a>
            </div>
        </div>

        <!-- Recent Activity / Updates -->
        <div class="updates-section">
            <h5 class="section-title"><?= Yii::t('general', 'System Updates') ?></h5>
            <div class="updates-card">
                <div class="update-item latest">
                    <div class="update-badge">
                        <span class="material-icon">new_releases</span>
                    </div>
                    <div class="update-content">
                        <div class="update-header">
                            <span class="update-title"><?= Yii::t('general', 'Latest Update') ?></span>
                            <span class="update-date">January 25, 2021</span>
                        </div>
                        <p class="update-text">Added doctor examination notes for patients. A new note can be created by the doctor from patient profile page.</p>
                    </div>
                </div>
                <div class="update-item">
                    <div class="update-badge">
                        <span class="material-icon">update</span>
                    </div>
                    <div class="update-content">
                        <div class="update-header">
                            <span class="update-title"><?= Yii::t('general', 'Update') ?></span>
                            <span class="update-date">January 22, 2021</span>
                        </div>
                        <p class="update-text">Added the ability to add a new medicine to the list when creating a prescription for the patient.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
