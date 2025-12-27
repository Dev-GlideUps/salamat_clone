<?php

namespace admin\models;

use Yii;
use clinic\models\Doctor as BaseDoctor;

/**
 * This is the model class for table "{{%doctor}}".
 *
 * @property int $id
 * @property string $name
 * @property int $speciality
 * @property string $description
 * @property int $experience
 * @property string $mobile
 * @property string $language
 * @property string $photo
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class Doctor extends BaseDoctor
{
    public function getPhotoUrl()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/doctor.svg');
        }
        return \yii\helpers\Url::to(["/clinics/doctors/photo/$this->photo"]);
    }

    public function getPhotoThumb()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/doctor.svg');
        }
        return \yii\helpers\Url::to(["/clinics/doctors/thumbnail/$this->photo"]);
    }
}
