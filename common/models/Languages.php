<?php

namespace common\models;

use Yii;
use yii\base\Model;

class Languages extends Model
{
    static function list($localization = false) {
        if ($localization) {
            return [
                "ara" => "العربية",
                "eng" => "English",
            ];
        }

        return [
            "ara" => Yii::t("lang", "Arabic"),
            "eng" => Yii::t("lang", "English"),
        ];
    }
}