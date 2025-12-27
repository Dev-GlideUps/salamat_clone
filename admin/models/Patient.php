<?php

namespace admin\models;

use Yii;
use patient\models\Patient as BasePatient;

class Patient extends BasePatient
{
    public function getPhotoUrl()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/patients/photo/$this->photo"]);
    }

    public function getPhotoThumb()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/patients/thumbnail/$this->photo"]);
    }
}
