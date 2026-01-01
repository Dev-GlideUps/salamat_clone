<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Country;
use clinic\models\Branch;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\AppointmentScheduleSearch */
/* @var $model patient\models\Patient */

$country = new Country();
$activeClinic = Yii::$app->user->identity->active_clinic;
$branchesList = Branch::find()->where(['clinic_id' => $activeClinic])->select('name')->indexBy('id')->column();

$this->title = Yii::t('clinic', 'Appointments');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');

if ($searchModel->branch_id !== null && strlen($searchModel->branch_id) > 0 && count($branchesList) > 1) {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index', 'AppointmentScheduleSearch' => ['date' => $searchModel->date]]];
    $this->params['breadcrumbs'][] = $branchesList[$searchModel->branch_id];
} else {
    $this->params['breadcrumbs'][] = $this->title;
}

$formatter = Yii::$app->formatter;

// timeline scale (relative to 1 hour)
$scale = 4;
?>

<div class="appointments-page">
    <!-- Page Header -->
    <div class="page-header-card">
        <div class="container-custom">
            <div class="header-content">
                <div class="header-info">
                    <div class="header-icon">
                        <span class="material-icon">calendar_month</span>
                    </div>
                    <div class="header-text">
                        <h4 class="header-title"><?= Html::encode($this->title) ?></h4>
                        <p class="header-subtitle">
                            <span class="material-icon">event</span>
                            <?= $formatter->asDate($searchModel->date, 'full') ?>
                        </p>
                    </div>
                </div>
                <div class="header-actions">
                    <?= Html::a('<span class="material-icon">view_list</span>', ['list'], [
                        'class' => 'action-btn',
                        'title' => Yii::t('clinic', 'List View'),
                    ]) ?>
                    <?= Html::button('<span class="material-icon">filter_list</span><span class="btn-text">' . Yii::t('general', 'Filters') . '</span>', [
                        'class' => 'action-btn primary',
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#appointments-search',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->render('_index_filters', ['model' => $searchModel, 'branches' => $branchesList]); ?>

<?php
$hours = [
    '12:00 AM',
    '1:00 AM',
    '2:00 AM',
    '3:00 AM',
    '4:00 AM',
    '5:00 AM',
    '6:00 AM',
    '7:00 AM',
    '8:00 AM',
    '9:00 AM',
    '10:00 AM',
    '11:00 AM',
    '12:00 PM',
    '1:00 PM',
    '2:00 PM',
    '3:00 PM',
    '4:00 PM',
    '5:00 PM',
    '6:00 PM',
    '7:00 PM',
    '8:00 PM',
    '9:00 PM',
    '10:00 PM',
    '11:00 PM',
    '12:00 AM',
];
$firstHour = 0;
$lastHour = 0;
$totalHours = 24;

$startingHour = null;
$endingHour = null;
foreach ($branches as $branch) {
    $branch = $branch['model'];
    if ($startingHour === null || date('G', strtotime($startingHour)) > date('G', strtotime($branch->schedule_starting))) {
        $startingHour = date('g:i A', strtotime($branch->schedule_starting));
    }
    if ($endingHour === null || date('G', strtotime($endingHour)) < date('G', strtotime($branch->schedule_ending)) || date('G', strtotime($branch->schedule_ending)) == 0) {
        if ($endingHour != '12:00 AM') {
            $endingHour = date('g:i A', strtotime($branch->schedule_ending));
        }
    }
}
if ($startingHour !== null && $endingHour !== null) {
    $firstHour = (int) date('G', strtotime($startingHour));
    $lastHour = (int) date('G', strtotime($endingHour));
    $totalHours = 0;
    $hours = [$startingHour];
    $startingTime = strtotime("{$searchModel->date} {$startingHour}");
    $endingTime = strtotime("{$searchModel->date} {$endingHour}");
    if ($endingHour == '12:00 AM') {
        $endingTime = strtotime("+1 day", $endingTime);
    }
    do {
        $startingTime = strtotime('+1 hour', $startingTime);
        $startingHour = date('g:i A', $startingTime);
        $hours[] = $startingHour;
        $totalHours++;
    } while ($startingTime < $endingTime);
}
?>

<?php if (empty($branches) || empty($hours)) { ?>
    <div class="container-custom">
        <div class="empty-state">
            <div class="empty-state-icon">
                <span class="material-icon">event_busy</span>
            </div>
            <h5 class="empty-state-title"><?= Yii::t('clinic', 'No appointments!') ?></h5>
            <p class="empty-state-text"><?= Yii::t('clinic', "Couldn't find any appointment. try using the filters.") ?></p>
            <?= Html::button('<span class="material-icon">add</span>' . Yii::t('clinic', 'New appointment'), [
                'class' => 'empty-state-action',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#add-new-appointment',
                ],
            ]) ?>
        </div>
    </div>
<?php } else { ?>
    <div id="appointments-schedule-container" class="modern-schedule">
        <div class="schedule-content">
            <table class="appointments-schedule mb-3">
                <thead>
                <tr class="branches">
                    <th class="day-hours"></th>
                    <?php foreach ($branches as $branch) {
                        $span = count($branch['doctors']);
                    ?>
                    <th class="branch" colspan="<?= $span ?>">
                        <?= Html::a('<span class="material-icon">location_on</span>' . $branch['model']->name, ['index', 'AppointmentScheduleSearch' => ['branch_id' => $branch['model']->id, 'date' => $searchModel->date]]) ?>
                    </th>
                    <?php } ?>
                </tr>
                <tr class="doctors">
                    <th class="day-hours"></th>
                    <?php foreach ($branches as $branch) { ?>
                    <?php foreach ($branch['doctors'] as $doctor) { ?>
                        <th class="doctor">
                            <span class="doctor-avatar"><span class="material-icon">person</span></span>
                            <span class="doctor-name"><?= $doctor['model']->name ?></span>
                        </th>
                    <?php } ?>
                    <?php } ?>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="day-hours">
                            <?php for ($i = 0; $i < count($hours); $i++) {echo '<div class="one-hour-line '.($i + 1 == count($hours) ? 'last-child' : '').'"><div class="day-hour">'.$hours[$i].'</div></div>';} ?>
                            <?php if ($searchModel->date == date('Y-m-d')) { ?>
                                <div class="timeline">
                                    <div class="current-time md-theme-dark">12:00 AM</div>
                                </div>
                            <?php } ?>
                        </td>

                        <?php foreach ($branches as $branch) { ?>
                        <?php foreach ($branch['doctors'] as $doctor) { ?>
                        <td>
                            <?php for ($i = 0; $i < count($hours); $i++) {echo '<div class="one-hour-line '.($i + 1 == count($hours) ? 'last-child' : '').'"></div>';} ?>

                            <?php
                            foreach ($doctor['appointments'] as $item) {
                                $from = explode(':', substr($item->time, 0, -3));
                                $fromNum = ($from[0] * 60 + $from[1]) * $scale;

                                if ($from[0] == 12 && substr($item->time, -2) == "AM") {
                                    $fromNum = $from[1] * $scale;
                                }
                                if ($from[0] < 12 && substr($item->time, -2) == "PM") {
                                    $fromNum += 12 * 60 * $scale;
                                }

                                $toNum = $item->duration * $scale;

                                $class = 'dropdown appointment';
                                if ($item->status == $item::STATUS_NO_SHOW || $item->status == $item::STATUS_CANCELED) {
                                    $class .= ' disabled';
                                }

                                $fromNum = $fromNum - ($firstHour * 60 * $scale);
                                if ($fromNum < 0 || $fromNum > ($totalHours * 60 * $scale)) {
                                    continue;
                                }

                                $serviceTitle = isset($item->servicetitle['title']) ? $item->servicetitle['title'] : '';
                            ?>
                                <div class="<?= $class ?>" style="top: <?= $fromNum ?>px; height: <?= $toNum ?>px;">
                                    <button type="button" class="appointment-status appointment-status-<?= $item->status ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="appointment-patient"><?= $item->patient->name ?></div>
                                        <div class="appointment-time">
                                            <span class="material-icon">schedule</span>
                                            <?= $item->time ?> - <?= $item->end_time ?>
                                        </div>
                                        <?php if (!empty($serviceTitle)) { ?>
                                        <div class="appointment-service"><?= $serviceTitle ?></div>
                                        <?php } ?>

                                        <?php if ($item->status == $item::STATUS_CONFIRMED) { ?>
                                        <div class="appointment-meta">
                                            <span class="material-icon">check_circle</span>
                                            <?= $formatter->asDate($item->confirmed_at) ?>
                                        </div>
                                        <?php } ?>

                                        <?php if ($item->status == $item::STATUS_WAITING || $item->status == $item::STATUS_WALK_IN) { ?>
                                        <div class="appointment-meta">
                                            <span class="material-icon">login</span>
                                            <?= $formatter->asTime($item->check_in_at, 'short') ?>
                                        </div>
                                        <?php } ?>

                                        <div class="appointment-icons">
                                        <?php if ($item->invoice !== null) { ?>
                                            <?php if ($item->invoice->balance == 0) { ?>
                                            <span class="material-icon paid">payments</span>
                                            <?php } ?>
                                            <span class="material-icon">receipt</span>
                                        <?php } ?>
                                        </div>
                                    </button>
                                    <div class="dropdown-menu appointment-dropdown">
                                        <div class="dropdown-header">
                                            <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="dropdown-time">
                                                <span class="material-icon">schedule</span>
                                                <span><?= $item->time ?> - <?= $item->end_time ?></span>
                                            </a>
                                            <span class="dropdown-date"><?= $formatter->asDate($item->date) ?></span>
                                        </div>

                                        <div class="dropdown-status appointment-status-<?= $item->status ?>">
                                            <span class="status-dot"></span>
                                            <?= $item::statusList()[$item->status] ?>
                                        </div>

                                        <a class="dropdown-patient" href="<?= Url::to(['/clinic/patients/view', 'id' => $item->patient->id]) ?>">
                                            <div class="patient-avatar">
                                                <span class="material-icon">person</span>
                                            </div>
                                            <div class="patient-info">
                                                <span class="patient-name"><?= $item->patient->name ?></span>
                                                <span class="patient-alt"><?= $item->patient->name_alt ?></span>
                                            </div>
                                            <span class="material-icon arrow">chevron_right</span>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($searchModel->date == date('Y-m-d')) { ?>
                                <div class="timeline"></div>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
</div>

<!-- Floating Action Button -->
<div class="fab-container">
    <?= Html::button('<span class="material-icon">add</span><span class="fab-text">' . Yii::t('clinic', 'New appointment') . '</span>', [
        'class' => 'fab-button',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-new-appointment',
        ],
    ]) ?>
</div>

<!-- New Appointment Modal -->
<div class="modal fade modern-modal" id="add-new-appointment" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['create'], 'get', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-icon">
                    <span class="material-icon">person_add</span>
                </div>
                <div class="modal-title"><?= Yii::t('clinic', 'New Appointment') ?></div>
                <p class="modal-subtitle"><?= Yii::t('clinic', 'Enter patient information') ?></p>
            </div>
            <div class="modal-body">
                <div class="form-group modern-input">
                    <label for="patient-cpr"><?= Yii::t('patient', 'CPR') ?></label>
                    <div class="input-wrapper">
                        <span class="material-icon">badge</span>
                        <input type="text" id="patient-cpr" class="form-control" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'Enter CPR number') ?>">
                    </div>
                </div>
                <div class="form-group modern-input">
                    <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                    <select class="form-control bootstrap-select" name="nationality" id="patient-nationality" data-live-search="true">
                        <?php foreach($country->countriesList  as $key => $value) {
                            $options = [
                                'class' => 'font-italic',
                                'value' => $key,
                            ];
                            if($key == "BH") {
                                $options['selected'] = true;
                            }
                            echo Html::tag('option', $value, $options);
                        } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn secondary" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton('<span class="material-icon">arrow_forward</span>' . Yii::t('general', 'Next'), ['class' => 'modal-btn primary']) ?>
            </div>
        <?= Html::endForm() ?>
    </div>
</div>

<?php
if ($searchModel->date == date('Y-m-d')) {
$script = <<< JS
function updateCurrentTimeline() {
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var position = (((hours - $firstHour) * 60) + minutes) * $scale;
    var time = (hours % 12) + ":";

    if (minutes < 10) {
        time += "0";
    }
    time += minutes;

    if (hours < 12) {
        time += " AM";
    } else {
        time += " PM";
    }

    var maxScale = ($totalHours * 60 - 1) * $scale; // 24hr 60s - 1px
    if (position >= maxScale) {
        $('.appointments-schedule tbody td .timeline').addClass('d-none');
    }

    $('.appointments-schedule tbody td .timeline').css('top', position);
    $('.appointments-schedule tbody td .timeline > .current-time').text(time);

    return position;
}

updateCurrentTimeline();

var timelineInterval = setInterval(function () {
    var position = updateCurrentTimeline();
    var maxScale = ($totalHours * 60 - 1) * $scale; // 24hr 60s - 1px
    if (position >= maxScale) {
        $('.appointments-schedule tbody td .timeline').remove();
        clearInterval(timelineInterval);
    }
}, 3000);
JS;

$this->registerJs($script, $this::POS_END);
}
?>
