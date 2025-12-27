<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%patient_examination_note}}".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $branch_id
 * @property string $notes
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property ClinicBranch $branch
 * @property Patient $patient
 */
class PatientExamNotes extends \yii\db\ActiveRecord
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
        return '{{%patient_examination_note}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'branch_id', 'notes'], 'required'],
            [['patient_id', 'branch_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['notes'], 'string'],
            [['notes'], 'trim'],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['branch_id'], 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
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
            'notes' => Yii::t('clinic', 'Examination notes'),

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
}
