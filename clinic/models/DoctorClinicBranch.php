<?php

namespace clinic\models;

use Yii;
use yii\helpers\Json;
use common\models\WorkingHoursForm;

/**
 * This is the model class for table "{{%doctor_clinic_branch}}".
 *
 * @property int $doctor_id
 * @property int $branch_id
 * @property string $working_hours
 * @property int $status
 */
class DoctorClinicBranch extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    public $weekDays = [
        7 => false, // Sunday
        1 => false, // Monday
        2 => false, // Tuesday
        3 => false, // Wednesday
        4 => false, // Thursday
        5 => false, // Friday
        6 => false, // Saturday
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%doctor_clinic_branch}}';
    }

    public static function primaryKey()
    {
        return ['doctor_id', 'branch_id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'branch_id', 'status'], 'required'],
            [['doctor_id', 'branch_id', 'status'], 'integer'],
            ['branch_override', 'boolean'],
            ['weekDays', 'each', 'rule' => ['boolean']],
            [['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], 'string', 'max' => 255],
            [['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'], 'safe'],
            ['doctor_id', 'exist', 'targetClass' => Doctor::className(), 'targetAttribute' => ['doctor_id' => 'id']],
            ['branch_id', 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            ['branch_id', 'unique', 'targetAttribute' => ['doctor_id', 'branch_id'], 'message' => Yii::t('clinic', 'This doctor already have a schedule with the selected branch.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doctor_id' => Yii::t('clinic', 'Doctor'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'status' => Yii::t('general', 'Doctor status'),
            'branch_override' => Yii::t('clinic', 'Override branch schedule'),
            'sunday' => Yii::t('general', 'Sunday'),
            'monday' => Yii::t('general', 'Monday'),
            'tuesday' => Yii::t('general', 'Tuesday'),
            'wednesday' => Yii::t('general', 'Wednesday'),
            'thursday' => Yii::t('general', 'Thursday'),
            'friday' => Yii::t('general', 'Friday'),
            'saturday' => Yii::t('general', 'Saturday'),
        ];
    }

    public static function statusList() {
        return [
            self::STATUS_INACTIVE => Yii::t('general', 'Unavailable'),
            self::STATUS_ACTIVE => Yii::t('general', 'Available'),
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

    public function getWorkingHours($checkOverride = false)
    {
        if ($checkOverride && $this->branch_override == false) {
            return $this->branch->workingHoursModel->workingHours;
        }

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

    public function isTimeWithinWorkingHours($date, $time, $duration)
    {
        $appTime = strtotime("{$date} {$time}");
        $appEndTime = strtotime("+{$duration} minutes", $appTime);

        $workingHours = $this->getWorkingHours(true)[date('N', strtotime($date))];

        if (empty($workingHours)) {
            // No working hours defined, allow full-day appointments
            return true;
        }

        foreach ($workingHours as $shift) {
            $from = strtotime("{$date} {$shift['from']}");
            $to = ($shift['to'] === '12:00 AM') 
                ? strtotime('+1 day', strtotime("{$date} 00:00"))
                : strtotime("{$date} {$shift['to']}");

            if ($appTime >= $from && $appEndTime <= $to) {
                return true; // Time falls within a valid shift
            }
        }

        return false; // Time is outside working hours
    }

    public function isTimeOverlapping($date, $time, $duration)
    {
        $appTime = strtotime("{$date} {$time}");
        $appEndTime = strtotime("+{$duration} minutes", $appTime);

        foreach ($this->appointments as $appointment) {
            if (in_array($appointment->status, [Appointment::STATUS_NO_SHOW, Appointment::STATUS_CANCELED])) {
                continue; // Skip non-active appointments
            }

            $itemTime = strtotime("{$appointment->date} {$appointment->time}");
            $itemEndTime = strtotime("{$appointment->date} {$appointment->end_time}");

            // Check if appointment times overlap
            if (($appTime >= $itemTime && $appTime < $itemEndTime) ||
                ($appEndTime > $itemTime && $appEndTime <= $itemEndTime) ||
                ($itemTime >= $appTime && $itemTime < $appEndTime) ||
                ($itemEndTime > $appTime && $itemEndTime <= $appEndTime)) {
                return true; // Overlapping found
            }
        }

        return false; // No overlap
    }



    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->alias('b');
    }

    public function getBranchWorkingHours()
    {
        return $this->hasOne(BranchWorkingHours::className(), ['branch_id' => 'branch_id'])->alias('bwh');
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'doctor_id'])->alias('d');
    }

    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['doctor_id' => 'doctor_id', 'branch_id' => 'branch_id'])->alias('app');
    }

    public function getDoctorServices()
    {
        return $this->hasMany(DoctorService::className(), ['doctor_id' => 'doctor_id', 'branch_id' => 'branch_id'])->alias('s');
    }

    public function getServices()
    {
        $services = $this->doctorServices;
        if (empty($services)) {
            return $this->branch->services;
        }

        return $services;
    }
}
