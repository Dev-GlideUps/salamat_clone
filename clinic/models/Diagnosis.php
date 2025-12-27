<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use admin\models\DiagnosisCode;

/**
 * This is the model class for table "{{%patient_diagnosis}}".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string|null $code
 * @property string $description
 * @property string|null $notes
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Diagnosis extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%patient_diagnosis}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'branch_id', 'description'], 'required'],
            [['patient_id', 'branch_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['notes'], 'string'],
            [['notes', 'description'], 'trim'],
            [['code', 'description'], 'string', 'max' => 255],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['branch_id'], 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['code'], 'exist', 'targetClass' => DiagnosisCode::className(), 'targetAttribute' => ['code' => 'code']],
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
            'branch_id' => Yii::t('clinic', 'Branch'),
            'code' => Yii::t('general', 'Code'),
            'description' => Yii::t('general', 'Description'),
            'notes' => Yii::t('clinic', 'Doctor notes'),

            'created_by' => Yii::t('clinic', 'Doctor'),
            'updated_by' => Yii::t('general', 'Updated by'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->alias('p');
    }
    
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'created_by'])->alias('d');
    }
    
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->alias('b');
    }
    
    public function getSickLeave()
    {
        return $this->hasOne(SickLeave::className(), ['diagnosis_id' => 'id'])->alias('sl');
    }
}
