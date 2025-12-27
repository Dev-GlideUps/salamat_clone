<?php

namespace app\models;

use clinic\models\Patient;
use Yii;

/**
 * This is the model class for table "patient_consent".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $consent_id
 * @property string|null $consent_date
 * @property string $signature
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PatientConsent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patient_consent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'consent_id', 'signature'], 'required'],
            [['patient_id', 'consent_id', 'created_at', 'updated_at'], 'integer'],
            [['consent_date','cpr','private_number','doctor_signature','doctor_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Patient Name',
            'consent_id' => 'Consent Name',
            'consent_date' => 'Consent Date',
            'signature' => "Patient's Signature",
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPatient(){
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    public function getConsent(){
        return $this->hasOne(ConsentForm::className(), ['id' => 'consent_id']);
    }
}
