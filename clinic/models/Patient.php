<?php

namespace clinic\models;

use Yii;
use patient\models\Patient as BasePatient;

class Patient extends BasePatient
{
    public function getClinicPatient()
    {
        return $this->hasOne(ClinicPatient::className(), ['patient_id' => 'id'])->where(['cp.clinic_id' => Yii::$app->user->identity->active_clinic])->alias('cp');
    }

    public function getProfileRef()
    {
        return $this->clinicPatient->profile_ref;
    }

    public function getPhotoUrl()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/clinic/patients/photo/$this->photo"]);
    }

    public function getPhotoThumb()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/clinic/patients/thumbnail/$this->photo"]);
    }
}
