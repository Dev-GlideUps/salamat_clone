<?php

namespace clinic\controllers;

use Yii;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\db\Query;
use yii\helpers\Json;
use clinic\models\Branch;
use clinic\models\Appointment;
use clinic\models\Patient;
use clinic\models\Diagnosis;
use clinic\models\Prescription;
use clinic\models\SickLeave;
use clinic\models\Invoice;

/**
 * AnalyticsController implements analytics charts.
 */
class AnalyticsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => $this->_accessControl,
            'rules' => [
                [
                    // All actions
                    'allow' => true,
                    'roles' => ['View analytics'],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all analytics charts.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $clinicId = Yii::$app->user->identity->active_clinic;
        $startingDate = date('Y-m-01', strtotime('-9 months'));

        $darkTheme = Yii::$app->user->identity->dark_theme;

        $params = array_merge(
            [
                'darkTheme' => $darkTheme,
            ],
            $this->getAppointmentsData($clinicId, $startingDate),
            $this->getPatientsData($clinicId, $startingDate),
            $this->getDiagnosesData($clinicId, $startingDate),
            $this->getPaymentData($clinicId, $startingDate),
            $this->getInvoicesData($clinicId, $startingDate, $darkTheme)
        );
        return $this->render('index', $params);
    }

    private function getAppointmentsData($clinicId, $startingDate)
    {
        $models = Appointment::find()->alias('app')->joinWith('branch')->where(['b.clinic_id' => $clinicId])->andFilterWhere(['>=', 'date', $startingDate])->orderBy(['date' => SORT_ASC, 'time' => SORT_ASC])->all();

        $allAppointments = [
            'total' => 0,
            Appointment::STATUS_PENDING => 0,
            Appointment::STATUS_WALK_IN => 0,
            Appointment::STATUS_CONFIRMED => 0,
            Appointment::STATUS_WAITING => 0,
            Appointment::STATUS_NO_SHOW => 0,
            Appointment::STATUS_CANCELED => 0,
            Appointment::STATUS_COMPLETED => 0,
            Appointment::STATUS_TENTATIVE => 0,
        ];

        $appointmentsLabels = [];
        $appointmentsData = [
            0 => new \stdClass(), // pending
            1 => new \stdClass(), // walk in
            2 => new \stdClass(), // confirmed
            3 => new \stdClass(), // waiting
            4 => new \stdClass(), // no show
            5 => new \stdClass(), // canceled
            6 => new \stdClass(), // completed
            7 => new \stdClass(), // total
        ];
        $appointmentsData[0]->label = Appointment::statusList()[Appointment::STATUS_PENDING];
        $appointmentsData[1]->label = Appointment::statusList()[Appointment::STATUS_WALK_IN];
        $appointmentsData[2]->label = Appointment::statusList()[Appointment::STATUS_CONFIRMED];
        $appointmentsData[3]->label = Appointment::statusList()[Appointment::STATUS_WAITING];
        $appointmentsData[4]->label = Appointment::statusList()[Appointment::STATUS_NO_SHOW];
        $appointmentsData[5]->label = Appointment::statusList()[Appointment::STATUS_CANCELED];
        $appointmentsData[6]->label = Appointment::statusList()[Appointment::STATUS_COMPLETED];
        $appointmentsData[7]->label = 'Total';
        $appointmentsData[0]->borderColor = 'rgba(25, 118, 210, 0.6)';
        $appointmentsData[1]->borderColor = 'rgba(0, 191, 165, 0.6)';
        $appointmentsData[2]->borderColor = 'rgba(255, 152, 0, 0.6)';
        $appointmentsData[3]->borderColor = 'rgba(255, 235, 59, 0.6)';
        $appointmentsData[4]->borderColor = 'rgba(120, 144, 156, 0.6)';
        $appointmentsData[5]->borderColor = 'rgba(229, 57, 53, 0.6)';
        $appointmentsData[6]->borderColor = 'rgba(139, 195, 74, 0.6)';
        $appointmentsData[7]->borderColor = 'rgba(158, 157, 36, 0.7)';
        for ($i = 0; $i <= 7; $i++) {
            $appointmentsData[$i]->data = [];
            $appointmentsData[$i]->lineTension = 0;
            $appointmentsData[$i]->borderWidth = 2;
            $appointmentsData[$i]->backgroundColor = 'transparent';
        }
        $monthsData = [];
        // echo "<pre>";print_r($allAppointments);die;
        foreach ($models as $model) {
            $allAppointments['total']++;
            $allAppointments[$model->status]++;
            

            $date = date('M Y', strtotime($model->date));
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'total' => 0,
                    Appointment::STATUS_PENDING => 0,
                    Appointment::STATUS_WALK_IN => 0,
                    Appointment::STATUS_CONFIRMED => 0,
                    Appointment::STATUS_WAITING => 0,
                    Appointment::STATUS_NO_SHOW => 0,
                    Appointment::STATUS_CANCELED => 0,
                    Appointment::STATUS_COMPLETED => 0,
                    Appointment::STATUS_TENTATIVE => 0,
                ];
            }

            $monthsData[$date]['total']++;
            $monthsData[$date][$model->status]++;
        }

        foreach($monthsData as $label => $data) {
            $appointmentsLabels[] = $label;
            $appointmentsData[0]->data[] = $data[Appointment::STATUS_PENDING];
            $appointmentsData[1]->data[] = $data[Appointment::STATUS_WALK_IN];
            $appointmentsData[2]->data[] = $data[Appointment::STATUS_CONFIRMED];
            $appointmentsData[3]->data[] = $data[Appointment::STATUS_WAITING];
            $appointmentsData[4]->data[] = $data[Appointment::STATUS_NO_SHOW];
            $appointmentsData[5]->data[] = $data[Appointment::STATUS_CANCELED];
            $appointmentsData[6]->data[] = $data[Appointment::STATUS_COMPLETED];
            $appointmentsData[7]->data[] = $data['total'];
        }

        return [
            'allAppointments' => $allAppointments,
            'appointmentsLabels' => Json::encode($appointmentsLabels),
            'appointmentsData' => Json::encode($appointmentsData),
        ];
    }

    private function getPaymentData($clinicId, $startingDate)
    {
        $models = Invoice::find()->joinWith(['clinic','payments'])->where(['c.id' => $clinicId])->orderBy(['ip.created_at' => SORT_ASC])->all();
        $allPayment = [
                    'total' => 0,
                    '0' => 0,
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,

                ];
        foreach ($models as $model) {
           if(isset($model->payments[0])){
            $allPayment['total']++;
            if ($model->payments[0]->payment_method == 0) {
                // $monthsData[$date]['male']++;
                $allPayment['0']++;
            }
            if ($model->payments[0]->payment_method == 1) {
                // $monthsData[$date]['male']++;
                $allPayment['1']++;
            }
            if ($model->payments[0]->payment_method == 2) {
                // $monthsData[$date]['male']++;
                $allPayment['2']++;
            }
            if ($model->payments[0]->payment_method == 3) {
                // $monthsData[$date]['male']++;
                $allPayment['3']++;
            }
            if ($model->payments[0]->payment_method == 4) {
                // $monthsData[$date]['male']++;
                $allPayment['3']++;
            }
            if ($model->payments[0]->payment_method == 5) {
                // $monthsData[$date]['male']++;
                $allPayment['3']++;
            }

           }
        }
        return [
            'allPayment' => $allPayment,
            'paymentLabels' => Json::encode($models),
         
        ];
    }

    private function getPatientsData($clinicId, $startingDate)
    {
        $models = Patient::find()->joinWith('clinicPatient')->where(['cp.clinic_id' => $clinicId])->andFilterWhere(['>=', 'cp.created_at', strtotime($startingDate)])->orderBy(['cp.created_at' => SORT_ASC])->all();

        $allPatients = [
            'total' => 0,
            'male' => 0,
            'female' => 0,
        ];

        $patientsLabels = [];
        $patientsData = [
            0 => new \stdClass(), // male
            1 => new \stdClass(), // female
            2 => new \stdClass(), // total
        ];
        $patientsData[0]->label = 'Male patients';
        $patientsData[1]->label = 'Female patients';
        $patientsData[2]->label = 'Total';
        for ($i = 0; $i <= 2; $i++) {
            $patientsData[$i]->data = [];
            $patientsData[$i]->lineTension = 0;
            $patientsData[$i]->borderWidth = 2;
            $patientsData[$i]->borderColor = 'rgba(199, 199, 46, 0.7)';
            $patientsData[$i]->backgroundColor = 'rgba(199, 199, 46, 0.2)';
        }
        $patientsData[2]->borderColor = 'rgba(158, 157, 36, 0.7)';
        $patientsData[2]->backgroundColor = 'rgba(158, 157, 36, 0.2)';
        $monthsData = [];

        foreach ($models as $model) {
            $date = date('M Y', $model->clinicPatient->created_at);
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'total' => 0,
                    'male' => 0,
                    'female' => 0,
                ];
            }

            $monthsData[$date]['total']++;
            $allPatients['total']++;
            if ($model->gender == $model::GENDER_MALE) {
                $monthsData[$date]['male']++;
                $allPatients['male']++;
            }
            if ($model->gender == $model::GENDER_FEMALE) {
                $monthsData[$date]['female']++;
                $allPatients['female']++;
            }
        }

        foreach($monthsData as $label => $data) {
            $patientsLabels[] = $label;
            $patientsData[0]->data[] = $data['male'];
            $patientsData[1]->data[] = $data['female'];
            $patientsData[2]->data[] = $data['total'];
        }

        return [
            'allPatients' => $allPatients,
            'patientsLabels' => Json::encode($patientsLabels),
            'patientsData' => Json::encode($patientsData),
        ];
    }

    private function getDiagnosesData($clinicId, $startingDate)
    {
        $diagnoses = Diagnosis::find()->alias('dg')->joinWith('branch')->where(['b.clinic_id' => $clinicId])->andFilterWhere(['>=', 'dg.created_at', strtotime($startingDate)])->orderBy(['dg.created_at' => SORT_ASC])->all();
        $prescriptions = Prescription::find()->alias('pr')->joinWith('branch')->where(['b.clinic_id' => $clinicId])->andFilterWhere(['>=', 'pr.created_at', strtotime($startingDate)])->orderBy(['pr.created_at' => SORT_ASC])->all();
        $sickLeaves = SickLeave::find()->alias('sl')->joinWith('branch')->where(['b.clinic_id' => $clinicId])->andFilterWhere(['>=', 'sl.created_at', strtotime($startingDate)])->orderBy(['sl.created_at' => SORT_ASC])->all();

        $allDiagnoses = [
            'diagnoses' => 0,
            'prescriptions' => 0,
            'sick-leaves' => 0,
        ];

        $diagnosesLabels = [];
        $diagnosesData = [
            0 => new \stdClass(), // diagnoses
            1 => new \stdClass(), // prescriptions
            2 => new \stdClass(), // sick-leaves
        ];
        $diagnosesData[0]->label = 'Diagnoses';
        $diagnosesData[0]->type = 'bar';
        $diagnosesData[0]->data = [];
        $diagnosesData[0]->borderWidth = 2;
        $diagnosesData[0]->borderColor = 'rgba(158, 157, 36, 0.7)';
        $diagnosesData[0]->backgroundColor = 'rgba(158, 157, 36, 0.2)';

        $diagnosesData[1]->label = 'Prescriptions';
        $diagnosesData[2]->label = 'Sick leaves';
        for ($i = 1; $i <= 2; $i++) {
            $diagnosesData[$i]->type = 'line';
            $diagnosesData[$i]->data = [];
            $diagnosesData[$i]->lineTension = 0;
            $diagnosesData[$i]->borderWidth = 2;
            $diagnosesData[$i]->borderColor = 'rgba(199, 199, 46, 0.7)';
            $diagnosesData[$i]->backgroundColor = 'rgba(199, 199, 46, 0.2)';
        }
        $monthsData = [];

        foreach ($diagnoses as $model) {
            $date = date('M Y', $model->created_at);
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'diagnoses' => 0,
                    'prescriptions' => 0,
                    'sick-leaves' => 0,
                ];
            }

            $monthsData[$date]['diagnoses']++;
            $allDiagnoses['diagnoses']++;
        }

        foreach ($prescriptions as $model) {
            $date = date('M Y', $model->created_at);
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'diagnoses' => 0,
                    'prescriptions' => 0,
                    'sick-leaves' => 0,
                ];
            }

            $monthsData[$date]['prescriptions']++;
            $allDiagnoses['prescriptions']++;
        }

        foreach ($sickLeaves as $model) {
            $date = date('M Y', $model->created_at);
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'diagnoses' => 0,
                    'prescriptions' => 0,
                    'sick-leaves' => 0,
                ];
            }

            $monthsData[$date]['sick-leaves']++;
            $allDiagnoses['sick-leaves']++;
        }

        foreach($monthsData as $label => $data) {
            $diagnosesLabels[] = $label;
            $diagnosesData[0]->data[] = $data['diagnoses'];
            $diagnosesData[1]->data[] = $data['prescriptions'];
            $diagnosesData[2]->data[] = $data['sick-leaves'];
        }

        return [
            'allDiagnoses' => $allDiagnoses,
            'diagnosesLabels' => Json::encode($diagnosesLabels),
            'diagnosesData' => Json::encode($diagnosesData),
        ];
    }

    private function getInvoicesData($clinicId, $startingDate, $darkTheme)
    {
        $models = Invoice::find()->alias('i')->joinWith('branch')->where(['b.clinic_id' => $clinicId, 'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere(['>=', 'i.created_at', strtotime($startingDate)])->orderBy(['i.created_at' => SORT_ASC])->all();

        $allInvoices = [
            'paid' => 0,
            'balance' => 0,
            'total' => 0,
        ];

        $invoicesLabels = [];
        $invoicesData = [
            0 => new \stdClass(), // paid
            1 => new \stdClass(), // balance
            2 => new \stdClass(), // total
        ];
        $invoicesData[0]->label = 'Paid amount';
        $invoicesData[1]->label = 'Balance due';
        $invoicesData[2]->label = 'Total';
        for ($i = 0; $i <= 2; $i++) {
            $invoicesData[$i]->data = [];
            $invoicesData[$i]->borderWidth = 2;
            $invoicesData[$i]->borderColor = 'rgba(199, 199, 46, 0.7)';
            $invoicesData[$i]->backgroundColor = 'rgba(199, 199, 46, 0.2)';
        }
        $invoicesData[0]->borderColor = 'rgba(158, 157, 36, 0.7)';
        $invoicesData[0]->backgroundColor = 'rgba(158, 157, 36, 0.2)';
        $invoicesData[2]->borderColor = $darkTheme ? 'rgba(255, 255, 255, 0.6)' : 'rgba(0, 0, 0, 0.48)';
        $invoicesData[2]->backgroundColor = $darkTheme ? 'rgba(255, 255, 255, 0.2)' : 'rgba(0, 0, 0, 0.16)';
        $monthsData = [];

        foreach ($models as $model) {
            $date = date('M Y', $model->created_at);
            if (!isset($monthsData[$date])) {
                $monthsData[$date] = [
                    'paid' => 0,
                    'balance' => 0,
                    'total' => 0,
                ];
            }

            $monthsData[$date]['paid'] += $model->paid;
            $allInvoices['paid'] += $model->paid;

            $monthsData[$date]['balance'] += $model->balance;
            $allInvoices['balance'] += $model->balance;

            $monthsData[$date]['total'] += $model->total;
            $allInvoices['total'] += $model->total;
        }

        foreach($monthsData as $label => $data) {
            $invoicesLabels[] = $label;
            $invoicesData[0]->data[] = $data['paid'];
            $invoicesData[1]->data[] = $data['balance'];
            $invoicesData[2]->data[] = $data['total'];
        }

        return [
            'allInvoices' => $allInvoices,
            'invoicesLabels' => Json::encode($invoicesLabels),
            'invoicesData' => Json::encode($invoicesData),
        ];
    }
}
