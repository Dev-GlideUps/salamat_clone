<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use clinic\models\Branch;
use clinic\models\Appointment;
use clinic\models\clinic\AppointmentSms;

class ClinicController extends Controller
{
    public function actionCloseAppointments()
    {
        $branches = Branch::find()->where(['!=', 'auto_closing', 0])->all();
        $currentTime = time();

        foreach ($branches as $branch) {
            $appointments = $branch->getAppointments()->where([
                'status' => [
                    Appointment::STATUS_TENTATIVE,
                    Appointment::STATUS_PENDING,
                    Appointment::STATUS_CONFIRMED,
                    Appointment::STATUS_WAITING,
                    Appointment::STATUS_WALK_IN,
                ],
            ])->all();
            
            foreach ($appointments as $appointment) {
                $appointmentTime = strtotime("{$appointment->date} {$appointment->time}");
                $closingTime = strtotime("+{$branch->auto_closing} hours", $appointmentTime);

                if ($closingTime <= $currentTime) {
                    switch ($appointment->status) {
                        case Appointment::STATUS_TENTATIVE:
                        case Appointment::STATUS_PENDING:
                            $appointment->updateAttributes(['status' => Appointment::STATUS_CANCELED]);
                        break;
                        case Appointment::STATUS_CONFIRMED:
                            $appointment->updateAttributes(['status' => Appointment::STATUS_NO_SHOW]);
                        break;
                        case Appointment::STATUS_WAITING:
                        case Appointment::STATUS_WALK_IN:
                            $appointment->updateAttributes(['status' => Appointment::STATUS_COMPLETED]);
                        break;
                    }
                }
            }
        }
    }

    public function actionSendAppointmentsSms()
    {
        $notifications = AppointmentSms::find()->where(['status' => AppointmentSms::STATUS_PENDING])->andWhere(['<=', 'send_at', time()])->limit(16)->all();

        foreach ($notifications as $item) {
            $item->sendSMS();
        }
    }
}