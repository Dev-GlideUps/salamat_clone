<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%clinic_branch}}".
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $location
 * @property string $working_hours
 * @property array $coordinates
 * @property int $created_at
 * @property int $updated_at
 * @property int $block
 *
 * @property Clinic $clinic
 */
class Branch extends \yii\db\ActiveRecord
{
    public $coordinatesInput;
    public $weekDays = [
        7 => false, // Sunday
        1 => false, // Monday
        2 => false, // Tuesday
        3 => false, // Wednesday
        4 => false, // Thursday
        5 => false, // Friday
        6 => false, // Saturday
    ];

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            // BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clinic_branch}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'name', 'location'], 'required'],
            [['clinic_id', 'created_at', 'updated_at', 'auto_closing','block'], 'integer'],
            [['name', 'name_alt', 'phone', 'address', 'location'], 'string', 'max' => 255],
            [['clinic_id'], 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            ['auto_closing', 'default', 'value' => 0],
            ['coordinatesInput', 'each', 'rule' => ['string']],
            ['weekDays', 'each', 'rule' => ['boolean']],
            [['schedule_starting', 'schedule_ending'], 'time', 'format' => 'php:H:i:s'],
            ['schedule_ending', 'checkScheduleTimes'],
        ];
    }

    public function checkScheduleTimes($attribute, $params)
    {
        $start = (int) date('H', strtotime($this->schedule_starting));
        $end = (int) date('H', strtotime($this->schedule_ending));
        if ($start == 0 || $end == 0) {
            return;
        }
        if ($start >= $end) {
            $this->addError($attribute, Yii::t('general', 'Ending time should be after starting time'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'clinic_id' => Yii::t('clinic', 'Clinic / Hospital'),
            'name' => Yii::t('clinic', 'Branch Name (English)'),
            'name_alt' => Yii::t('clinic', 'Branch Name (Arabic)'),
            'phone' => Yii::t('general', 'Phone'),
            'address' => Yii::t('general', 'Address'),
            'location' => Yii::t('general', 'Location'),
            'schedule_starting' => Yii::t('general', 'Starting time'),
            'schedule_ending' => Yii::t('general', 'Ending time'),
            'auto_closing' => Yii::t('clinic', 'Appointment auto-closing'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public static function getClosingTime($time = null)
    {
        $closingTimes = [
            0 => Yii::t('general', 'Never'),
            8 => Yii::t('general', 'After {n} hours', ['n' => 8]),
            12 => Yii::t('general', 'After {n} hours', ['n' => 12]),
            24 => Yii::t('general', 'After {n} hours', ['n' => 24]),
        ];
        if ($time === null) {
            return $closingTimes;
        }
        return $closingTimes[$time];
    }

    public function getContactNumber()
    {
        if (empty($this->phone)) {
            return $this->clinic->phone;
        }
        return $this->phone;
    }

    public function getLocationUrl()
    {
        $coordinates = $this->coordinates;
        return "https://www.google.com/maps/search/?api=1&query={$coordinates['latitude']},{$coordinates['longitude']}";
    }

    public function getCoordinates()
    {
        if (empty($this->location)) {
            return [];
        }

        return Json::decode($this->location);
    }

    public function setCoordinates($value)
    {
        $array = ArrayHelper::merge($this->coordinates, $value);

        $this->location = Json::encode($array);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c');
    }

    public function getWorkingHoursModel()
    {
        return $this->hasOne(BranchWorkingHours::className(), ['branch_id' => 'id'])->alias('wh');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorSchedule()
    {
        return $this->hasMany(DoctorClinicBranch::className(), ['branch_id' => 'id'])->alias('ds');
    }

    public function getDoctors()
    {
        return $this->hasMany(Doctor::className(), ['id' => 'doctor_id'])->via('doctorSchedule')->alias('d');
    }

    public function getServices()
    {
        return $this->hasMany(BranchService::className(), ['branch_id' => 'id'])->
        orderBy(['title' => SORT_ASC])->alias('s');    }

    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['branch_id' => 'id'])->alias('app');
    }
}
