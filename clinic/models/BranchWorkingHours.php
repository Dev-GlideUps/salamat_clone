<?php

namespace clinic\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use common\models\WorkingHoursForm;

/**
 * This is the model class for table "{{%clinic_working_hours}}".
 *
 * @property int $id
 * @property int $branch_id
 * @property string $sunday
 * @property string $monday
 * @property string $tuesday
 * @property string $wednesday
 * @property string $thursday
 * @property string $friday
 * @property string $saturday
 */
class BranchWorkingHours extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clinic_working_hours}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id'], 'required'],
            [['branch_id'], 'integer'],
            ['branch_id', 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], 'string', 'max' => 255],
            [['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'sunday' => Yii::t('general', 'Sunday'),
            'monday' => Yii::t('general', 'Monday'),
            'tuesday' => Yii::t('general', 'Tuesday'),
            'wednesday' => Yii::t('general', 'Wednesday'),
            'thursday' => Yii::t('general', 'Thursday'),
            'friday' => Yii::t('general', 'Friday'),
            'saturday' => Yii::t('general', 'Saturday'),
        ];
    }

    public function setWorkingHours($array)
    {
        foreach ($array as $dayNum => $shifts) {
            $data = null;

            if (is_array($shifts)) {
                $data = [];
                foreach ($shifts as $setOfHours) {
                    $data[] = [
                        'from' => $setOfHours->from,
                        'to' => $setOfHours->to,
                    ];
                }
                $data = Json::encode($data);
            }
            
            $this->{strtolower(WorkingHoursForm::DAYS[$dayNum])} = $data;
        }
    }

    public function getWorkingHours()
    {
        $array = WorkingHoursForm::DAYS;
        foreach ($array as $dayNum => $_) {
            if (empty($this->{strtolower(WorkingHoursForm::DAYS[$dayNum])})) {
                $array[$dayNum] = null;
            } else {
                $array[$dayNum] = Json::decode($this->{strtolower(WorkingHoursForm::DAYS[$dayNum])});
            }
        }

        return $array;
    }
}
