<?php

namespace admin\models;

use Yii;
use clinic\models\Clinic as BaseClinic;

/**
 * This is the model class for table "{{%clinic}}".
 *
 */
class Clinic extends BaseClinic
{
    public function getLogoUrl()
    {
        if (empty($this->logo)) {
            return '';
        }
        return \yii\helpers\Url::to(["/clinics/logo/$this->logo"]);
    }
}
