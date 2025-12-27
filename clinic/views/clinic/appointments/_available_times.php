<?php
$appointmentsTimesList = [];
foreach ($appointments as $item) {
    if ($item->status != $item::STATUS_NO_SHOW && $item->status != $item::STATUS_CANCELED) {
        $appointmentsTimesList["{$date} {$item->time}"] = "{$date} {$item->end_time}";
    }
}
?>

<?php if ($workingHours === null) {
    echo $this->render('_ajax_error', ['message' => Yii::t('clinic', 'Branch is closed')]);
} else {
    $index = 0;
    $is24 = false;
    if (empty($workingHours)) {
        $workingHours[] = [
            'from' => strtotime("{$date} 00:00"),
            'to' => strtotime('+1 days', strtotime("{$date} 00:00")),
        ];
        $is24 = true;
    }
    foreach ($workingHours as $shift) {
        $from = $shift['from'];
        $to = $shift['to'];
        if (!$is24) {
            $from = strtotime("{$date} {$shift['from']}");
            if ($to == '12:00 AM') {
                $to = strtotime('+1 days', strtotime("{$date} 00:00"));
            } else {
                $to = strtotime("{$date} {$shift['to']}");
            }
        }
        while ($from < $to) {
            $appointmentTime = date('g:i A', $from);
            $disabled = '';
            foreach ($appointmentsTimesList as $reservedFrom => $reservedTo) {
                if ($from >= strtotime($reservedFrom) && $from < strtotime($reservedTo)) {
                    $disabled = 'disabled';
                    break;
                }
            }

            if ($from <= strtotime('-5 minutes')) {
                $disabled = 'disabled';
            }
            ?>
            <div class="custom-control custom-radio">
                <input type="radio" id="available-time-<?= $index ?>" class="custom-control-input" name="Appointment[time]" value="<?= $appointmentTime ?>" <?= $disabled ?>>
                <label class="custom-control-label" for="available-time-<?= $index ?>"><?= $appointmentTime ?></label>
            </div>
            <?php

            $from = strtotime("+15 minutes", $from);
            $index++;
        }
    }
}
?>