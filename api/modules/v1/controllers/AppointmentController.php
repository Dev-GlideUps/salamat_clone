<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Appointment Controller API
 */
class AppointmentController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Appointment';
}
