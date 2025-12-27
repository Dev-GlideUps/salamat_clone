<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Working Hours Form
 */
class WorkingHoursForm extends Model
{
    public $from;
    public $to;

    const DAYS = [
        7 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required', 'message' => Yii::t('general', 'Please fill in working hours form')],
            [['from', 'to'], 'time', 'format' => 'php:h:i A', 'message' => Yii::t('general', 'Invalid working hours form')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => Yii::t('general', 'From'),
            'to' => Yii::t('general', 'To'),
        ];
    }

    public static function renderWorkingHoursTable($data)
    {
        if (empty($data)) {
            return null;
        }

        $content = [];
        $content[] = Html::beginTag('div', ['class' => 'table-responsive']);
        $content[] = Html::beginTag('table', ['class' => 'working-hours-schedule']);

        $content[] = Html::beginTag('thead');
        $content[] = Html::beginTag('tr');
        $content[] = Html::tag('th', '', ['class' => 'day-hours']);
        foreach (self::DAYS as $dayStr) {
            $content[] = Html::tag('th', Yii::t('general', $dayStr));
        }
        $content[] = Html::endTag('tr');
        $content[] = Html::endTag('thead');
        $content[] = Html::beginTag('tbody');
        $content[] = Html::beginTag('tr');

        foreach (self::DAYS as $dayNum => $dayStr) {
            if ($dayNum == 7) {
                $content[] = Html::beginTag('td', ['class' => 'day-hours']);
                for ($i = 0; $i < 24; $i++) {
                    $hour = $i > 0 ? ($i / 12 >= 1 ? ($i % 12 == 0 ? 12 : $i % 12)." PM" : "$i AM") : '';
                    $content[] = Html::tag('div', Html::tag('div', $hour, ['class' => 'day-hour']), ['class' => 'one-hour-line']);
                }
                $content[] = Html::endTag('td');
            }

            $content[] = Html::beginTag('td');
            for ($i = 0; $i < 24; $i++) {
                $content[] = Html::tag('div', '', ['class' => 'one-hour-line']);
            }

            if ($data[$dayNum] !== null) {
                if (count($data[$dayNum]) == 0) {
                    $content[] = Html::beginTag('div', ['class' => 'set-of-hours all-24-hours']);
                    $content[] = Html::tag('div', Yii::t('general', '24 Hours'), ['class' => 'from']);
                    $content[] = Html::tag('div', Yii::t('general', '24 Hours'), ['class' => 'to']);
                    $content[] = Html::endTag('div');
                } else {
                    $scale = 0.5;
                    foreach ($data[$dayNum] as $shift) {
                        $from = explode(':', substr($shift['from'], 0, -3));
                        $fromNum = ($from[0] * 60 + $from[1]) * $scale;
                        if ($from[0] == 12 && substr($shift['from'], -2) == "AM") {
                            $fromNum = $from[1] * $scale;
                        }
                        if ($from[0] < 12 && substr($shift['from'], -2) == "PM") {
                            $fromNum += 12 * 60 * $scale;
                        }

                        $to = explode(':', substr($shift['to'], 0, -3));
                        $toNum = ($to[0] * 60 + $to[1]) * $scale - $fromNum;
                        if (($to[0] < 12 && substr($shift['to'], -2) == "PM") || ($to[0] == 12 && substr($shift['to'], -2) == "AM")) {
                            $toNum += 12 * 60 * $scale;
                        }

                        $content[] = Html::beginTag('div', ['class' => 'set-of-hours', 'style' => "top: {$fromNum}px; height: {$toNum}px;"]);
                        $content[] = Html::tag('div', $shift['from'], ['class' => 'from']);
                        $content[] = Html::tag('div', $shift['to'], ['class' => 'to']);
                        $content[] = Html::endTag('div');
                    }
                }
            }

            $content[] = Html::endTag('td');
        }
        
        $content[] = Html::endTag('tr');
        $content[] = Html::endTag('tbody');
        $content[] = Html::endTag('table');
        $content[] = Html::endTag('div');

        return implode("\n", $content);
    }
}
