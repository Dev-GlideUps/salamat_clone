<?php

namespace clinic\controllers\clinic;

use clinic\controllers\Controller;
use clinic\models\Appointment;
use clinic\models\AppointmentScheduleSearch;
use clinic\models\AppointmentSearch;
use clinic\models\ClinicPatient;
use clinic\models\DoctorClinicBranch;
use clinic\models\Invoice;
use clinic\models\Patient;
use clinic\models\clinic\AppointmentSms;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * AppointmentsController implements the CRUD actions for Appointment model.
 */
class AppointmentsController extends Controller
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
                    'actions' => ['index', 'list', 'view'],
                    'allow' => true,
                    'roles' => ['View appointments'],
                ],
                [
                    'actions' => ['available-times', 'create'],
                    'allow' => true,
                    'roles' => ['Create appointments'],
                ],
                [
                    'actions' => ['update', 'update-status', 'update-notes'],
                    'allow' => true,
                    'roles' => ['Update appointments'],
                ],
                [
                    'actions' => ['update-patient-phone'],
                    'allow' => true,
                    'roles' => ['Update patients'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'available-times' => ['POST'],
                'update-status' => ['POST'],
                'update-notes' => ['POST'],
                'update-patient-phone' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Appointment models.
     * @param array $branches = [
     *      branch_id => [
     *          model => Branch,
     *          doctors => [
     *              doctor_id => [
     *                  model => Doctor,
     *                  appointments => [Appointment],
     *              ],
     *              ...
     *          ],
     *          ...
     *      ],
     *      ...
     *  ];
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new AppointmentScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $branches = [];
        foreach ($dataProvider->models as $item) {
            if (!isset($branches[$item->branch_id])) {
                $branches[$item->branch_id] = [];
                $branches[$item->branch_id]['model'] = $item->branch;
            }
            
            // echo "<pre>";print_r($item);
            $branches[$item->branch_id]['doctors'][$item->doctor_id]['model'] = $item->doctor;
            $branches[$item->branch_id]['doctors'][$item->doctor_id]['schedule'] = $item->workingHours;
            $branches[$item->branch_id]['doctors'][$item->doctor_id]['appointments'] = $item->appointments;
        }
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'branches' => $branches,
        ]);
    }

    public function actionList()
    {
        $this->pushNavHistory();

        $searchModel = new AppointmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Appointment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->pushNavHistory();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Shows available times for an appointment.
     * @param date $date
     * @param integer $doctor_id
     * @param integer $branch_id
     * @return mixed
     */
    public function actionAvailableTimes($view, $id)
    {
        if (!Yii::$app->request->isAjax) {
            $this->redirect(['index']);
        }

        try {
            $date = Yii::$app->request->post('date');
            $doctor_id = Yii::$app->request->post('doctor_id');
            $branch_id = Yii::$app->request->post('branch_id');

            $model = DoctorClinicBranch::find()->alias('db')->joinWith('branch')->where([
                'db.doctor_id' => $doctor_id,
                'db.branch_id' => $branch_id,
                'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            ])->one();
            $workingHours = $model->getWorkingHours(true)[date('N', strtotime($date))];

            if ($view == 'update') {
                $appointments = $model->getAppointments()
                    ->where(['app.date' => $date])
                    ->andwhere(['!=', 'app.id', $id])
                    ->all();
            } else {
                $appointments = $model->getAppointments()->where(['app.date' => $date])->all();
            }

            return $this->renderPartial('_available_times', [
                'date' => $date,
                'workingHours' => $workingHours,
                'appointments' => $appointments,
            ]);
        } catch (\Exception $e) {
            return $this->renderPartial('_ajax_error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Creates a new Appointment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate($cpr, $nationality, $invoice_id = false)
    // {
    //     $cpr = trim($cpr);
    //     $nationality = trim($nationality);

    //     if (($patient = Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality])) === null) {
    //         $patient = new Patient(['cpr' => $cpr, 'nationality' => $nationality, 'phone_line' => '973']);
    //     }
        
    //     if (($clinicPatient = $patient->clinicPatient) === null) {
    //         $clinicPatient = new ClinicPatient();
    //     }

    //     $invoice = null;
    //     if (!empty($invoice_id) && ($invoice = Invoice::find()->alias('i')->joinWith('branch')->where(['i.id' => $invoice_id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) === null) {
    //         Yii::$app->session->setFlash('error', Yii::t('finance', "Couldn't find selected invoice"));
    //         return $this->navHistoryBack(['index']);
    //     }
    //     // validateInvoice
    //     $model = new Appointment(['status' => Appointment::STATUS_PENDING, 'date' => date('Y-m-d')]);

    //     if ($invoice !== null) {
    //         $model->invoice_id = $invoice->id;
    //         if (!$invoice->canAddAppointment) {
    //             Yii::$app->session->setFlash('error', Yii::t('finance', "Maximum number of appointments reached for the selected invoice"));
    //             return $this->navHistoryBack(['index']);
    //         }
    //     }

    //     if ($model->load(Yii::$app->request->post())) {
    //         if ($patient->isNewRecord && (!$patient->load(Yii::$app->request->post()) || !$patient->save())) {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create new petient"));
    //             goto CREATE_VIEW;
    //         }

    //         if ($patient->clinicPatient == null) {
    //             $clinicPatient->load(Yii::$app->request->post());
    //             $clinicPatient->clinic_id = Yii::$app->user->identity->active_clinic;
    //             $clinicPatient->patient_id = $patient->id;
    //             $clinicPatient->save();
    //         }

    //         $model->patient_id = $patient->id;
    //         $doctorBranchIDs = explode("-", $model->doctor_branch);
    //         $model->doctor_id = $doctorBranchIDs[0];
    //         $model->branch_id = $doctorBranchIDs[1];
    //         // $model->status = Appointment::STATUS_PENDING;

    //         $doctorBranch = DoctorClinicBranch::find()->alias('db')->joinWith('branch')->where([
    //             'db.doctor_id' => $model->doctor_id,
    //             'db.branch_id' => $model->branch_id,
    //             'b.clinic_id' => Yii::$app->user->identity->active_clinic,
    //         ])->one();

    //         if ($doctorBranch === null) {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Doctor / Branch"));
    //             goto CREATE_VIEW;
    //         }

    //         $service = null;
    //         foreach ($doctorBranch->services as $item) {
    //             if ($item->id == explode('-', $model->service_id)[2]) {
    //                 $service = $item;
    //             }
    //         }

    //         if ($service === null) {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Service"));
    //             goto CREATE_VIEW;
    //         }

    //         $model->service = Json::encode([
    //             'title' => $service->title,
    //             'title_alt' => $service->title_alt,
    //             'max_appointments' => $service->max_appointments,
    //         ]);
    //         $model->price = $service->price;
    //         $model->duration = $service->duration;

    //         $model->end_time = date('g:i A', strtotime("+{$model->duration} minutes", strtotime("{$model->date} {$model->time}")));

    //         $appTime = strtotime("{$model->date} {$model->time}");
    //         $appEndTime = strtotime("+{$model->duration} minutes", strtotime("{$model->date} {$model->time}"));

    //         $workingHours = $doctorBranch->getWorkingHours(true)[date('N', strtotime($model->date))];

    //         $outsideWorkingHours = true;
    //         if ($workingHours !== null) {
    //             foreach ($workingHours as $shift) {
    //                 $from = strtotime("{$model->date} {$shift['from']}");
    //                 if ($shift['to'] == '12:00 AM') {
    //                     $to = strtotime('+1 days', strtotime("{$model->date} 00:00"));
    //                 } else {
    //                     $to = strtotime("{$model->date} {$shift['to']}");
    //                 }

    //                 if ($appTime >= $from && $appEndTime <= $to) {
    //                     $outsideWorkingHours = false;
    //                     break;
    //                 }
    //             }
    //             if (empty($workingHours)) {
    //                 if ($appTime >= strtotime("{$model->date} 00:00") && $appEndTime <= strtotime('+1 days', strtotime("{$model->date} 00:00"))) {
    //                     $outsideWorkingHours = false;
    //                 }
    //             }
    //         }

    //         if ($outsideWorkingHours) {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
    //             goto CREATE_VIEW;
    //         }

    //         foreach ($doctorBranch->appointments as $item) {
    //             if ($item->status != $item::STATUS_NO_SHOW && $item->status != $item::STATUS_CANCELED) {
    //                 $itemTime = strtotime("{$item->date} {$item->time}");
    //                 $itemEndTime = strtotime("{$item->date} {$item->end_time}");
    //                 if (($appTime >= $itemTime && $appTime < $itemEndTime) || ($appEndTime > $itemTime && $appEndTime <= $itemEndTime) || ($itemTime >= $appTime && $itemTime < $appEndTime) || ($itemEndTime > $appTime && $itemEndTime <= $appEndTime)) {
    //                     Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
    //                     goto CREATE_VIEW;
    //                 }
    //             }
    //         }

    //         if ($appTime <= strtotime('-5 minutes')) {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
    //             goto CREATE_VIEW;
    //         }

    //         if ($model->status == Appointment::STATUS_WALK_IN) {
    //             $model->check_in_at = time();
    //         } else {
    //             $model->status = Appointment::STATUS_PENDING;
    //         }

    //         if ($model->save()) {
    //             if (Yii::$app->user->identity->activeClinic->appointment_sms) {
    //                 $sms = new AppointmentSms([
    //                     'clinic_id' => $clinicPatient->clinic_id,
    //                     'appointment_id' => $model->id,
    //                     'mobile' => $patient->phone_line.$patient->phone,
    //                     'message' => $model->appointmentSMS,
    //                     'status' => AppointmentSms::STATUS_PENDING,
    //                     'send_at' => $model->appointmentSMSTime,
    //                 ]);
    //                 $sms->save();
    //             }

    //             Yii::$app->session->setFlash('success', Yii::t('clinic', "Appointment created successfully"));
    //             return $this->navHistoryBack(['view', 'id' => $model->id]);
    //         } else {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create new appointment"));
    //         }
    //     }

    //     CREATE_VIEW:
    //     return $this->render('create', [
    //         'model' => $model,
    //         'patient' => $patient,
    //         'clinicPatient' => $clinicPatient,
    //     ]);
    // }

    public function actionCreate($cpr, $nationality, $invoice_id = false)
    {
        $cpr = trim($cpr);
        $nationality = trim($nationality);

        // Retrieve or create Patient and ClinicPatient
        $patient = $this->getOrCreatePatient($cpr, $nationality);
        $clinicPatient = $patient->clinicPatient ?? new ClinicPatient();

        // Validate Invoice
        $invoice = $this->validateInvoice($invoice_id);
        if ($invoice === false) {
            return $this->navHistoryBack(['index']);
        }

        $model = new Appointment([
            'status' => Appointment::STATUS_PENDING,
            'date' => date('Y-m-d'),
            'invoice_id' => $invoice->id ?? null,
        ]);

        // Load POST data and validate
        if ($model->load(Yii::$app->request->post())) {
            if (!$this->processPatient($model,$patient, $clinicPatient) || !$this->processAppointment($model, $invoice)) {
                return $this->renderCreateView($model, $patient, $clinicPatient);
            }
            
            // Create SMS notification
            $this->createAppointmentSms($model, $clinicPatient, $patient);

            Yii::$app->session->setFlash('success', Yii::t('clinic', "Appointment created successfully"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        return $this->renderCreateView($model, $patient, $clinicPatient);
    }

// Helper to retrieve or create a Patient
    private function getOrCreatePatient($cpr, $nationality)
    {
        return Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality]) 
            ?? new Patient(['cpr' => $cpr, 'nationality' => $nationality, 'phone_line' => '973']);
    }

// Helper to validate Invoice
    private function validateInvoice($invoice_id)
    {
        if (empty($invoice_id)) {
            return null;
        }

        $invoice = Invoice::find()
            ->alias('i')
            ->joinWith('branch')
            ->where([
                'i.id' => $invoice_id,
                'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            ])->one();

        if ($invoice === null) {
            Yii::$app->session->setFlash('error', Yii::t('finance', "Couldn't find selected invoice"));
            return false;
        }

        if (!$invoice->canAddAppointment) {
            Yii::$app->session->setFlash('error', Yii::t('finance', "Maximum number of appointments reached for the selected invoice"));
            return false;
        }

        return $invoice;
    }

// Helper to process Patient and ClinicPatient
    private function processPatient($model, $patient, $clinicPatient)
    {
        if ($patient->isNewRecord && (!$patient->load(Yii::$app->request->post()) || !$patient->save())) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create new patient"));
            return false;
        }

        if ($clinicPatient->isNewRecord) {
            $clinicPatient->load(Yii::$app->request->post());
            $clinicPatient->clinic_id = Yii::$app->user->identity->active_clinic;
            $clinicPatient->patient_id = $patient->id;
            $clinicPatient->save();
        }

        $model->patient_id = $patient->id;
        return true;
    }

// Helper to process Appointment
    private function processAppointment($model, $invoice)
    {
        $doctorBranch = $this->validateDoctorBranch($model);
        if (!$doctorBranch) {
            return false;
        }

        $service = $this->validateService($model, $doctorBranch);
        if (!$service) {
            return false;
        }

        if (!$this->validateAppointmentTime($model, $doctorBranch)) {
            return false;
        }

        $model->populateFromService($service);
        $model->setEndTime();
        return $model->save();
    }

// Helper to validate DoctorBranch
    private function validateDoctorBranch($model)
    {
        [$doctor_id, $branch_id] = explode("-", $model->doctor_branch);
        $model->doctor_id = $doctor_id;
        $model->branch_id = $branch_id;

        $doctorBranch = DoctorClinicBranch::find()
            ->alias('db')
            ->joinWith('branch')
            ->where([
                'db.doctor_id' => $model->doctor_id,
                'db.branch_id' => $model->branch_id,
                'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            ])->one();

        if ($doctorBranch === null) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Doctor / Branch"));
            return false;
        }

        return $doctorBranch;
    }

// Helper to validate Service
    private function validateService($model, $doctorBranch)
    {
        foreach ($doctorBranch->services as $item) {
            if ($item->id == explode('-', $model->service_id)[2]) {
                return $item;
            }
        }

        Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Service"));
        return null;
    }

// Helper to validate Appointment Time
    private function validateAppointmentTime($model, $doctorBranch)
    {
        if (!$doctorBranch->isTimeWithinWorkingHours($model->date, $model->time, $model->duration)) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
            return false;
        }

        if ($doctorBranch->isTimeOverlapping($model->date, $model->time, $model->duration)) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
            return false;
        }

        return true;
    }

// Helper to create Appointment SMS
    private function createAppointmentSms($model, $clinicPatient, $patient)
    {
        if (Yii::$app->user->identity->activeClinic->appointment_sms) {
            $sms = new AppointmentSms([
                'clinic_id' => $clinicPatient->clinic_id,
                'appointment_id' => $model->id,
                'mobile' => $patient->phone_line . $patient->phone,
                'message' => $model->appointmentSMS,
                'status' => AppointmentSms::STATUS_PENDING,
                'send_at' => $model->appointmentSMSTime,
            ]);
            $sms->save();
        }
    }

// Helper to render Create View
    private function renderCreateView($model, $patient, $clinicPatient)
    {
        return $this->render('create', [
            'model' => $model,
            'patient' => $patient,
            'clinicPatient' => $clinicPatient,
        ]);
    }


    /**
     * Updates an existing Appointment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Appointment::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't update the appointment"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            // $doctorBranchIDs = explode("-", $model->doctor_branch);
            // $model->doctor_id = $doctorBranchIDs[0];
            // $model->branch_id = $doctorBranchIDs[1];

            $doctorBranch = DoctorClinicBranch::find()->alias('db')->joinWith('branch')->where([
                'db.doctor_id' => $model->doctor_id,
                'db.branch_id' => $model->branch_id,
                'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            ])->one();

            if ($doctorBranch === null) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Doctor / Branch"));
                goto UPDATE_VIEW;
            }

            // $service = null;
            // foreach ($doctorBranch->services as $item) {
            //     if ($item->id == explode('-', $model->service_id)[2]) {
            //         $service = $item;
            //     }
            // }

            // if ($service === null) {
            //     Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't find selected Service"));
            //     goto UPDATE_VIEW;
            // }


            $model->end_time = date('g:i A', strtotime("+{$model->duration} minutes", strtotime("{$model->date} {$model->time}")));

            $appTime = strtotime("{$model->date} {$model->time}");
            $appEndTime = strtotime("+{$model->duration} minutes", strtotime("{$model->date} {$model->time}"));

            $workingHours = $doctorBranch->getWorkingHours(true)[date('N', strtotime($model->date))];

            $outsideWorkingHours = true;
            if ($workingHours !== null) {
                foreach ($workingHours as $shift) {
                    $from = strtotime("{$model->date} {$shift['from']}");
                    if ($shift['to'] == '12:00 AM') {
                        $to = strtotime('+1 days', strtotime("{$model->date} 00:00"));
                    } else {
                        $to = strtotime("{$model->date} {$shift['to']}");
                    }

                    if ($appTime >= $from && $appEndTime <= $to) {
                        $outsideWorkingHours = false;
                        break;
                    }
                }
                if (empty($workingHours)) {
                    if ($appTime >= strtotime("{$model->date} 00:00") && $appEndTime <= strtotime('+1 days', strtotime("{$model->date} 00:00"))) {
                        $outsideWorkingHours = false;
                    }
                }
            }

            if ($outsideWorkingHours) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
                goto UPDATE_VIEW;
            }

            foreach ($doctorBranch->appointments as $item) {
                if ($item->status != $item::STATUS_NO_SHOW && $item->status != $item::STATUS_CANCELED && $item->id != $model->id) {
                    $itemTime = strtotime("{$item->date} {$item->time}");
                    $itemEndTime = strtotime("{$item->date} {$item->end_time}");
                    if (($appTime >= $itemTime && $appTime < $itemEndTime) || ($appEndTime > $itemTime && $appEndTime <= $itemEndTime) || ($itemTime >= $appTime && $itemTime < $appEndTime) || ($itemEndTime > $appTime && $itemEndTime <= $appEndTime)) {
                        Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
                        goto UPDATE_VIEW;
                    }
                }
            }

            if ($appTime <= strtotime('-5 minutes')) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected time is not available"));
                goto UPDATE_VIEW;
            }


            if ($model->save(true, ['date', 'time', 'end_time'])) {
                AppointmentSms::updateAll(['status' => AppointmentSms::STATUS_CANCELED], ['appointment_id' => $model->id, 'status' => AppointmentSms::STATUS_PENDING]);

                if (Yii::$app->user->identity->activeClinic->appointment_sms) {
                    $sms = new AppointmentSms([
                        'clinic_id' => $model->patient->clinicPatient->clinic_id,
                        'appointment_id' => $model->id,
                        'mobile' => $model->patient->phone_line.$model->patient->phone,
                        'message' => $model->appointmentSMS,
                        'status' => AppointmentSms::STATUS_PENDING,
                        'send_at' => $model->appointmentSMSTime,
                    ]);
                    $sms->save();
                }

                Yii::$app->session->setFlash('success', Yii::t('clinic', "Appointment updated successfully"));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't update the appointment"));
            }
        }

        UPDATE_VIEW:
        return $this->render('update', [
            'model' => $model,
            'patient' => $model->patient,
        ]);


    }

    public function actionUpdateStatus($id)
    {
        $model = $this->findModel($id);

        $date24 = strtotime('+1 day', strtotime("{$model->date} {$model->time}"));
        if (time() > $date24) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "This action cannot be performed after passing 24 hours"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($model->status != Appointment::STATUS_PENDING && $model->status != Appointment::STATUS_CONFIRMED && $model->status != Appointment::STATUS_WAITING && $model->status != Appointment::STATUS_WALK_IN) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only pending, confirmed, waiting and walk in status can be updated"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        $model->load(Yii::$app->request->post());

        if ($model->status == Appointment::STATUS_WAITING || $model->status == Appointment::STATUS_NO_SHOW || $model->status == Appointment::STATUS_COMPLETED) {
            if (strtotime(date('Y-m-d')) < strtotime($model->date)) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "This action cannot be performed before appointment date"));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            }
        }

        if ($model->status == Appointment::STATUS_CONFIRMED) {
            $model->confirmed_at = time();
        }

        if ($model->status == Appointment::STATUS_WAITING || $model->status == Appointment::STATUS_WALK_IN) {
            $model->check_in_at = time();
        }

        if ($model->save(true, ['status', 'confirmed_at', 'check_in_at'])) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Appointment status updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't update appointment status"));
        }

        return $this->navHistoryBack(['view', 'id' => $model->id]);
    }

    public function actionUpdateNotes($id)
    {
        $model = $this->findModel($id);

        $date24 = strtotime('+1 day', strtotime("{$model->date} {$model->time}"));
        if (time() > $date24) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "This action cannot be performed after passing 24 hours"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($model->status != Appointment::STATUS_PENDING && $model->status != Appointment::STATUS_CONFIRMED && $model->status != Appointment::STATUS_WAITING && $model->status != Appointment::STATUS_WALK_IN) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only pending, confirmed, waiting and walk in status can be updated"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if (!Yii::$app->user->identity->isDoctor || $model->doctor_id != Yii::$app->user->identity->doctor->id) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only appointment doctor can perform this action"));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        $model->load(Yii::$app->request->post());

        if ($model->save(true, ['notes'])) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Appointment notes updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't update appointment notes"));
        }

        return $this->navHistoryBack(['view', 'id' => $model->id]);
    }

    public function actionUpdatePatientPhone($id)
    {
        $model = Patient::find()->alias('p')->joinWith('clinicPatient')->where(['p.id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $model->load(Yii::$app->request->post());

        if ($model->save(true, ['phone_line', 'phone'])) {
            Yii::$app->session->setFlash('success', Yii::t('patient', "Patient contact number updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't update Patient contact number"));
        }

        return $this->redirect(['create', 'cpr' => $model->cpr, 'nationality' => $model->nationality]);
    }

    /**
     * Finds the Appointment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Appointment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Appointment::find()->alias('app')->joinWith('branch')->where(['app.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
