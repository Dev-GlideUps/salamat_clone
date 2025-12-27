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

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                    <h6 class="text-hint"><?= $formatter->asDate($searchModel->date, 'full') ?></h6>
                </div>
            </div>
        </div>

        <?= $this->render('_index_filters', ['model' => $searchModel, 'branches' => $branchesList]); ?>
    </div>
</div>

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
    <div class="row justify-content-center appointment-index mt-3">
        <div class="col text-center">
            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No appointments!') ?></h5>
                <p class="text-hint p-0"><?= Yii::t('clinic', "Couldn't find any appointment. try using the filters.") ?></p>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div id="appointments-schedule-container">
    <div class="schedule-content">
        <table class="appointments-schedule mb-3">
            <thead>
            <tr class="branches">
                <th class="day-hours"></th>
                <?php foreach ($branches as $branch) {
                    $span = count($branch['doctors']);
                ?>
                <th class="branch" colspan="<?= $span ?>"><?= Html::a($branch['model']->name, ['index', 'AppointmentScheduleSearch' => ['branch_id' => $branch['model']->id, 'date' => $searchModel->date]]) ?></th>
                <?php } ?>
            </tr>
            <tr class="doctors">
                <th class="day-hours"></th>
                <?php foreach ($branches as $branch) { ?>
                <?php foreach ($branch['doctors'] as $doctor) { ?>
                    <th class="doctor"><?= $doctor['model']->name ?></th>
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
                        ?>
                            <div class="<?= $class ?>" style="top: <?= $fromNum ?>px; height: <?= $toNum ?>px;">
                                <button type="button" class="appointment-status appointment-status-<?= $item->status ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="mdt-subtitle-2 text-left my-1" style="padding: 0 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $item->patient->name ?></div>
                                    <div class="mdt-overline text-left my-1" style="padding: 0 12px;">
                                        <?= $item->time ?> - <?= $item->end_time ?>
                                       
                                    </div>
                                    <div class="mdt-overline text-left my-1" style="padding: 0 12px;">
                                    <?= print_r($item->servicetitle['title']);  ?>

                                       
                                    </div>
                                    <div class="mdt-overline text-left my-1" style="padding: 0 12px;">
                                    </div>

                                    <?php if ($item->status == $item::STATUS_CONFIRMED) { ?>
                                    <div class="mdt-overline text-left my-1" style="padding: 0 12px;">
                                        <?= $item->getAttributeLabel('confirmed_at') ?> <?= $formatter->asDate($item->confirmed_at) ?>
                                    </div>
                                    <?php } ?>

                                    <?php if ($item->status == $item::STATUS_WAITING || $item->status == $item::STATUS_WALK_IN) { ?>
                                    <div class="mdt-overline text-left my-1" style="padding: 0 12px;">
                                        <?= $item->getAttributeLabel('check_in_at') ?> <?= $formatter->asTime($item->check_in_at, 'short') ?>
                                    </div>
                                    <?php } ?>

                                    <div class="icons p-1">
                                    <?php if ($item->invoice !== null) { ?>
                                        <?php if ($item->invoice->balance == 0) { ?>
                                        <div class="material-icon mr-1">payments</div>
                                        <?php } ?>
                                        <div class="material-icon">receipt</div>
                                    <?php } ?>
                                    </div>
                                </button>
                                <div class="dropdown-menu py-0 m-0">
                                    <a class="mdc-list-item" href="<?= Url::to(['view', 'id' => $item->id]) ?>">
                                        <div class="text">
                                            <?= $item->time ?> - <?= $item->end_time ?>
                                            <div class="secondary"><?= $formatter->asDate($item->date) ?></div>
                                        </div>
                                    </a>
                                    
                                    <div class="appointment-status appointment-status-<?= $item->status ?> mdt-subtitle-2">
                                        <?= $item::statusList()[$item->status] ?>
                                    </div>
                                    
                                    <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $item->patient->id]) ?>">
                                        <div class="graphic material-icon bg-salamat-color">person</div>
                                        <div class="text">
                                            <?= $item->patient->name ?>
                                            <div class="secondary"><?= $item->patient->name_alt ?></div>
                                        </div>
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

<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('clinic', 'New appointment'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-new-appointment',
        ],
    ]) ?>
</div>

<div class="modal fade" id="add-new-appointment" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['create'], 'get', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Enter patient information') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-label-group form-group">
                    <input type="text" id="patient-cpr" class="form-control" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'CPR') ?>">
                    <label for="patient-cpr"><?= Yii::t('patient', 'CPR') ?></label>
                </div>
                <div class="form-label-group form-group">
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
                    <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Next'), ['class' => 'mdc-button salamat-color']) ?>
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
