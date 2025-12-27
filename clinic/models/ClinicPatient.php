<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%patient_clinic_relation}}".
 *
 * @property int $clinic_id
 * @property int $patient_id
 * @property string|null $profile_ref
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Clinic $clinic
 * @property Patient $patient
 */
class ClinicPatient extends \yii\db\ActiveRecord
{
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
        return '{{%patient_clinic_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'patient_id'], 'required'],
            [['clinic_id', 'patient_id', 'created_at', 'updated_at'], 'integer'],
            [['profile_ref'], 'string', 'max' => 255],
            [['profile_ref'], 'unique', 'skipOnEmpty' => true, 'targetAttribute' => ['clinic_id', 'profile_ref'], 'message' => Yii::t('patient', 'Profile ID should be unique')],
            [['clinic_id', 'patient_id'], 'unique', 'targetAttribute' => ['clinic_id', 'patient_id'], 'message' => Yii::t('patient', 'Patient record already exist')],
            [['clinic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clinic_id' => Yii::t('clinic', 'Clinic'),
            'patient_id' => Yii::t('patient', 'Patient'),
            'profile_ref' => Yii::t('patient', 'Profile ID'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    /**
     * Gets query for [[Clinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }
}
