<?php

namespace clinic\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%appointment}}".
 *
 * @property int $id
 * @property string $service_id (doctor_id-branch_id-service_id)
 * @property string $doctor_branch (doctor_id-branch_id)
 * @property int $patient_id
 * @property int $doctor_id
 * @property int $branch_id
 * @property int|null $status
 * @property string $date
 * @property string $time
 * @property string $end_time
 * @property string $service
 * @property float $price
 * @property int|null $duration
 * @property string|null $notes
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property ClinicBranch $branch
 * @property Doctor $doctor
 * @property Patient $patient
 */
class Appointment extends \yii\db\ActiveRecord
{
    const SOURCE_CLINIC = 0;
    const SOURCE_PATIENT = 1;

    const STATUS_PENDING = 0;
    const STATUS_NO_SHOW = 1;
    const STATUS_CANCELED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CONFIRMED = 4;
    const STATUS_WAITING = 5;
    const STATUS_WALK_IN = 6;
    const STATUS_TENTATIVE = 7;

    public $doctor_branch;
    public $service_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%appointment}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
        ];
    }

    public static function statusList($status = null) {
        $list = [
            self::STATUS_TENTATIVE => Yii::t('general', 'Tentative'),
            self::STATUS_PENDING => Yii::t('general', 'Pending'),
            self::STATUS_WALK_IN => Yii::t('general', 'Walk in'),
            self::STATUS_CONFIRMED => Yii::t('general', 'Confirmed'),
            self::STATUS_WAITING => Yii::t('general', 'Waiting'),
            self::STATUS_NO_SHOW => Yii::t('general', 'No show'),
            self::STATUS_CANCELED => Yii::t('general', 'Canceled'),
            self::STATUS_COMPLETED => Yii::t('general', 'Completed'),
        ];

        if ($status !== null) {
            return $list[$status];
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'branch_id', 'date', 'time', 'end_time', 'service', 'doctor_branch', 'service_id'], 'required'],
            [['patient_id', 'doctor_id', 'branch_id', 'status', 'duration', 'confirmed_at', 'check_in_at', 'created_at', 'updated_at', 'invoice_id'], 'integer'],
            [['date', 'time', 'end_time'], 'safe'],
            [['time', 'end_time'], 'time', 'format' => 'php:g:i A'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['price'], 'number'],
            [['source'], 'default', 'value' => self::SOURCE_CLINIC],
            [['service', 'notes', 'service_id'], 'string'],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['invoice_id'], 'exist', 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['doctor_branch'], 'exist', 'targetRelation' => 'doctorBranch'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('clinic', 'ID'),
            'patient_id' => Yii::t('clinic', 'Patient'),
            'doctor_id' => Yii::t('clinic', 'Doctor'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'service_id' => Yii::t('clinic', 'Service'),
            'invoice_id' => Yii::t('clinic', 'Invoice'),
            'doctor_branch' => Yii::t('clinic', 'Doctor / Branch'),
            'status' => Yii::t('clinic', 'Appointment status'),
            'date' => Yii::t('clinic', 'Appointment date'),
            'time' => Yii::t('clinic', 'Appointment time'),
            'duration' => Yii::t('clinic', 'Appointment duration'),
            'notes' => Yii::t('clinic', 'Appointment notes'),
            'confirmed_at' => Yii::t('clinic', 'Confirmed at'),
            'check_in_at' => Yii::t('clinic', 'Checked in at'),
            'source' => Yii::t('clinic', 'Booking origin'),

            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->time = date('H:i:s', strtotime("{$this->date} {$this->time}"));
        $this->end_time = date('H:i:s', strtotime("{$this->date} {$this->end_time}"));
        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->time = date('g:i A', strtotime("{$this->date} {$this->time}"));
        $this->end_time = date('g:i A', strtotime("{$this->date} {$this->end_time}"));
    }

    public function getAppointmentSMS()
    {
        return "Reminder of your appointment on ".date('l Y-m-d g:i A', strtotime("{$this->date} {$this->time}"))." with {$this->doctor->name}, {$this->clinic->name}\n\nCall center: {$this->clinic->phone}";
    }

    public function getAppointmentSMSTime()
    {
        $appointmentTime = strtotime("{$this->date} {$this->time}");
        $currentTime = time();
        
        if ($currentTime < strtotime('-1 day', $appointmentTime)) {
            return strtotime('-1 day', $appointmentTime);
        }
        
        if ($currentTime < strtotime('-8 hours', $appointmentTime)) {
            return strtotime('-8 hours', $appointmentTime);
        }
        
        if ($currentTime < strtotime('-4 hours', $appointmentTime)) {
            return strtotime('-4 hours', $appointmentTime);
        }
    }

    public function getCanCreateInvoice()
    {
        return ($this->status == $this::STATUS_WALK_IN || $this->status == $this::STATUS_CONFIRMED || $this->status == $this::STATUS_WAITING || $this->status == $this::STATUS_COMPLETED) && ($this->invoice === null || $this->invoice->status == $this->invoice::STATUS_CANCELED);
    }

    public function getCanUpdateStatus()
    {
        return ($this->status == self::STATUS_TENTATIVE || $this->status == self::STATUS_PENDING || $this->status == self::STATUS_CONFIRMED || $this->status == self::STATUS_WAITING || $this->status == self::STATUS_WALK_IN);
    }
    
    public function getServiceTitle()
    {
        return Json::decode($this->service);
    }

    public function populateFromService($service)
    {
        $this->service = Json::encode([
            'title' => $service->title,
            'title_alt' => $service->title_alt,
            'max_appointments' => $service->max_appointments,
        ]);
        $this->price = $service->price;
        $this->duration = $service->duration;
    }

    public function setEndTime()
    {
        if (!empty($this->time) && !empty($this->duration)) {
            $this->end_time = date('g:i A', strtotime("+{$this->duration} minutes", strtotime($this->time)));
        }
    }



    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->alias('b');
    }

    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c')->via('branch');
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'doctor_id'])->alias('d');
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->alias('p');
    }

    public function getDoctorBranch()
    {
        return $this->hasOne(DoctorClinicBranch::className(), ['doctor_id' => 'doctor_id', 'branch_id' => 'branch_id'])->alias('db');
    }

    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id'])->alias('i');
    }


}
