<?php

namespace clinic\models;

use Yii;
use yii\base\Model;

/**
 *
 * @property int $user_id
 * @property int $clinic_id
 */
class ReportForm extends Model
{
    public $branch_id;
    public $starting_date;
    public $ending_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id'], 'integer'],
            [['starting_date', 'ending_date'], 'date', 'format' => 'php:Y-m-d'],
            [['starting_date', 'ending_date'], 'validateDates'],
            [['starting_date'], 'default', 'value' => date('Y-m-d', strtotime('-1 months'))],
            [['ending_date'], 'default', 'value' => date('Y-m-d')],
        ];

        // return [
        //     [['branch_id'], 'integer'],
        //     [['starting_date', 'ending_date'], 'datetime', 'format' => 'php:Y-m-d\TH:i'], // Use datetime format with time
        //     [['starting_date', 'ending_date'], 'validateDates'],
        //     [['starting_date'], 'default', 'value' => date('Y-m-d\TH:i', strtotime('-1 months'))], // Default with time
        //     [['ending_date'], 'default', 'value' => date('Y-m-d\TH:i')], // Default with time
        // ];
    }

    public function validateDates()
    {
        $diff = 0;
        // $start = strtotime("+{$diff} days", strtotime($this->starting_date));
        $start = strtotime($this->starting_date);
        $end = strtotime($this->ending_date);
        if($start > $end) {
            $this->addError('starting_date', Yii::t('general', 'Difference between {attribute1} and {attribute2} should be {diff} or more', [
                'attribute1' => Yii::t('general', 'Starting date'),
                'attribute2' => Yii::t('general', 'Ending date'),
                'diff' => Yii::t('general', '{num} days', ['num' => $diff]),
            ]));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branch_id' => Yii::t('clinic', 'Branch'),
            'starting_date' => Yii::t('general', 'Starting date'),
            'ending_date' => Yii::t('general', 'Ending date'),
        ];
    }
}
