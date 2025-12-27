<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%prescription}}".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $branch_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property ClinicBranch $branch
 * @property Patient $patient
 * @property PrescriptionItem[] $prescriptionItems
 */
class Prescription extends \yii\db\ActiveRecord
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
        return '{{%prescription}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'diagnosis_id', 'branch_id'], 'required'],
            [['patient_id', 'diagnosis_id', 'branch_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['diagnosis_id'], 'exist', 'targetClass' => Diagnosis::className(), 'targetAttribute' => ['diagnosis_id' => 'id']],
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
            'diagnosis_id' => Yii::t('patient', 'Diagnosis'),
            'branch_id' => Yii::t('clinic', 'Branch'),

            'created_by' => Yii::t('clinic', 'Doctor'),
            'updated_by' => Yii::t('general', 'Updated by'),
            'created_at' => Yii::t('patient', 'Prescription date'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
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
    
    public function getDiagnosis()
    {
        return $this->hasOne(Diagnosis::className(), ['id' => 'diagnosis_id'])->alias('dg');
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->alias('p');
    }
    
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'created_by'])->alias('d');
    }

    /**
     * Gets query for [[PrescriptionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(PrescriptionItem::className(), ['prescription_id' => 'id'])->alias('i');
    }
}
