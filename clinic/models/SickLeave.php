<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%patient_sick_leave}}".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property int $leave_type
 * @property int $advise
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property ClinicBranch $branch
 * @property Doctor $doctor
 * @property Patient $patient
 */
class SickLeave extends \yii\db\ActiveRecord
{
    const TYPE_SICK = 0;
    const TYPE_COMPANION = 1;
    
    const ADVISE_DUTY_UNFIT = 0;
    const ADVISE_DUTY_LIGHT = 1;
    const ADVISE_DUTY_FIT = 2;
    const ADVISE_EMPLOYEE_CARE = 3;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    public static function typeList() {
        return [
            self::TYPE_SICK => Yii::t('patient', 'Reported sick'),
            self::TYPE_COMPANION => Yii::t('patient', 'Accompanying patient'),
        ];
    }

    public static function adviseList() {
        return [
            self::ADVISE_DUTY_UNFIT => Yii::t('patient', "Patient unfit for duty"),
            self::ADVISE_DUTY_LIGHT => Yii::t('patient', 'Advised light duty'),
            self::ADVISE_DUTY_FIT => Yii::t('patient', 'Patient fit for duty'),
            self::ADVISE_EMPLOYEE_CARE => Yii::t('patient', "Patient needs employee's care"),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%patient_sick_leave}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'diagnosis_id', 'leave_type', 'advise', 'days', 'commencing_on'], 'required'],
            [['patient_id', 'diagnosis_id', 'leave_type', 'advise', 'days', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['commencing_on'], 'date', 'format' => 'php:Y-m-d'],
            [['diagnosis_id'], 'exist', 'targetClass' => Diagnosis::className(), 'targetAttribute' => ['diagnosis_id' => 'id']],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'patient_id' => Yii::t('patient', 'Patient'),
            'diagnosis_id' => Yii::t('clinic', 'Diagnosis'),
            'leave_type' => Yii::t('patient', 'Leave type'),
            'advise' => Yii::t('patient', 'Doctor advise'),
            'days' => Yii::t('patient', 'Number of days'),
            'commencing_on' => Yii::t('patient', 'Commencing on'),
            'created_by' => Yii::t('clinic', 'Doctor'),
            
            'created_at' => Yii::t('patient', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
            'updated_by' => Yii::t('general', 'Updater'),
        ];
    }

    /**
     * Gets query for [[Diagnosis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosis()
    {
        return $this->hasOne(Diagnosis::className(), ['id' => 'diagnosis_id'])->alias('dg');
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
    
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->via('diagnosis')->alias('b');
    }
    
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->via('branch')->alias('cl');
    }
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->alias('c');
    }
    
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'created_by'])->alias('d');
    }
}
