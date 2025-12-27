<?php

namespace clinic\controllers;

use Yii;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use clinic\models\Branch;
use clinic\models\Appointment;
use clinic\models\Patient;
use clinic\models\Diagnosis;
use clinic\models\Prescription;
use clinic\models\SickLeave;
use clinic\models\Invoice;
use clinic\models\InvoicePayment;
use clinic\models\ReportForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * ReportsController implements analytics charts.
 */
class ReportsController extends Controller
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
                    'roles' => ['View reports'],
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

        return $this->render('index', [
        ]);
    }

    public function actionAppointments()
    {
        $this->pushNavHistory();

        $search = new ReportForm();
        $search->load(Yii::$app->request->queryParams);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $data = [
            'total' => 0,
            Appointment::STATUS_PENDING => 0,
            Appointment::STATUS_WALK_IN => 0,
            Appointment::STATUS_CONFIRMED => 0,
            Appointment::STATUS_WAITING => 0,
            Appointment::STATUS_NO_SHOW => 0,
            Appointment::STATUS_CANCELED => 0,
            Appointment::STATUS_COMPLETED => 0,
            Appointment::STATUS_TENTATIVE => 0,
            'doctors' => [],
            'branches' => [],
        ];

        if ($search->validate()) {
            $mainCondition = ['b.clinic_id' => $clinicId];
            if (!empty($search->branch_id)) {
                $mainCondition['b.id'] = $search->branch_id;
            }
            $models = Appointment::find()->alias('app')->joinWith(['branch', 'doctor'])->where($mainCondition)
                ->andFilterWhere(['>=', 'app.date', $search->starting_date])
                ->andFilterWhere(['<=', 'app.date', $search->ending_date])
                ->andFilterWhere(['between', 'app.date', $search->starting_date, $search->ending_date])
                ->orderBy(['app.date' => SORT_ASC, 'app.time' => SORT_ASC])->all();

            foreach ($models as $model) {
                $data['total']++;
                $data[$model->status]++;

                if (!isset($data['doctors'][$model->doctor_id])) {
                    $data['doctors'][$model->doctor_id] = [
                        'name' => $model->doctor->name,
                        'total' => 0,
                    ];
                }
                $data['doctors'][$model->doctor_id]['total']++;

                if (!isset($data['branches'][$model->branch_id])) {
                    $data['branches'][$model->branch_id] = [
                        'name' => $model->branch->name,
                        'total' => 0,
                    ];
                }
                $data['branches'][$model->branch_id]['total']++;
            }
        }

        return $this->render('appointments', [
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function actionPatients()
    {
        $this->pushNavHistory();

        $search = new ReportForm();
        $search->load(Yii::$app->request->queryParams);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $data = [
            'total' => 0,
            'male' => 0,
            'female' => 0,
            'unknown' => 0,
            'ages' => [
                0 => 0, // < 4
                1 => 0, // 5 - 14
                2 => 0, // 15 - 24
                3 => 0, // 25 - 64
                4 => 0, // 65+
                5 => 0, // unknown
            ],
            'status' => [
                Patient::STATUS_SINGLE => 0,
                Patient::STATUS_ENGAGED => 0,
                Patient::STATUS_MARRIED => 0,
                Patient::STATUS_WIDOWED => 0,
                Patient::STATUS_DIVORCED => 0,
            ],
            'blood' => [
                'O-' => 0,
                'O+' => 0,
                'A-' => 0,
                'A+' => 0,
                'B-' => 0,
                'B+' => 0,
                'AB-' => 0,
                'AB+' => 0,
            ],
        ];

        if ($search->validate(['starting_date', 'ending_date'])) {
            $models = Patient::find()->joinWith('clinicPatient')->where(['cp.clinic_id' => $clinicId])
                // ->andFilterWhere(['>=', 'cp.created_at', strtotime($search->starting_date)])
                // ->andFilterWhere(['<=', 'cp.created_at', (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->andFilterWhere(['between', 'cp.created_at', strtotime($search->starting_date), (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->orderBy(['cp.created_at' => SORT_ASC])->all();

            foreach ($models as $model) {
                $data['total']++;
                if ($model->gender == $model::GENDER_MALE) {
                    $data['male']++;
                }
                if ($model->gender == $model::GENDER_FEMALE) {
                    $data['female']++;
                }
                if ($model->gender === null) {
                    $data['unknown']++;
                }
                if (!empty($model->dob)) {
                    $dobYear = explode('-', $model->dob)[0];
                    $year = date('Y');
                    $age = $year - $dobYear;
                    if ($age < 5) {
                        $data['ages'][0]++;
                    } elseif ($age < 15) {
                        $data['ages'][1]++;
                    } elseif ($age < 25) {
                        $data['ages'][2]++;
                    } elseif ($age < 65) {
                        $data['ages'][3]++;
                    } else {
                        $data['ages'][4]++;
                    }
                } else {
                    $data['ages'][5]++;
                }
                if (!empty($model->blood_type)) {
                    $data['blood'][$model->blood_type]++;
                }

                if ($model->marital_status !== null) {
                    $data['status'][$model->marital_status]++;
                }
            }
        }

        return $this->render('patients', [
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function actionDiagnoses()
    {
        $this->pushNavHistory();

        $search = new ReportForm();
        $search->load(Yii::$app->request->queryParams);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $diagnoses = [];
        $totalCount = 0;

        if ($search->validate()) {
            $mainCondition = ['b.clinic_id' => $clinicId];
            if (!empty($search->branch_id)) {
                $mainCondition['b.id'] = $search->branch_id;
            }

            // $models = Diagnosis::find()->alias('dg')->joinWith(['branch', 'doctor', 'patient'])->where($mainCondition)
            //     ->andFilterWhere(['>=', 'dg.created_at', strtotime($search->starting_date)])
            //     ->andFilterWhere(['<=', 'dg.created_at', strtotime('+1 day', strtotime($search->ending_date) - 1)])
            //     ->orderBy(['dg.created_at' => SORT_ASC])->all();

            $query = new \yii\db\Query();
            $diagnoses = $query->select(['COUNT(dg.code) as total', 'dg.code', 'dg.description'])
                ->from('{{%patient_diagnosis}} dg')->leftJoin('{{%clinic_branch}} b', 'dg.branch_id = b.id')->where($mainCondition)
                ->andFilterWhere(['not', ['dg.code' => null]])
                ->andFilterWhere(['>', 'LENGTH(TRIM(dg.code))', 0])
                ->andFilterWhere(['>=', 'dg.created_at', strtotime($search->starting_date)])
                ->andFilterWhere(['<=', 'dg.created_at', strtotime('+1 day', strtotime($search->ending_date) - 1)])
                ->groupBy(['dg.code'])->orderBy(['total' => SORT_DESC])->limit(10)->all();

            foreach ($diagnoses as $item) {
                $totalCount += $item['total'];
            }
        }

        return $this->render('diagnoses', [
            'totalCount' => $totalCount,
            'diagnoses' => $diagnoses,
            'search' => $search,
        ]);
    }

    public function actionInvoices()
    {
        $this->pushNavHistory();

        $search = new ReportForm();
        $search->load(Yii::$app->request->queryParams);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $data = [
            'paid' => 0,
            'payments' => [
                InvoicePayment::METHOD_CASH => 0,
                InvoicePayment::METHOD_CHEQUE => 0,
                InvoicePayment::METHOD_DEBIT_CARD => 0,
                InvoicePayment::METHOD_CREDIT_CARD => 0,
                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                InvoicePayment::METHOD_BENEFIT_PAY => 0,
            ],
            'balance' => 0,
            'vat' => 0,
            'total' => 0,
            'doctors' => [],
        ];

        if ($search->validate()) {
            $mainCondition = ['b.clinic_id' => $clinicId, 'i.status' => Invoice::STATUS_ACTIVE];
            if (!empty($search->branch_id)) {
                $mainCondition['b.id'] = $search->branch_id;
            }
            $models = Invoice::find()->alias('i')->joinWith(['branch', 'payments', 'appointments'])->where($mainCondition)
                // ->andFilterWhere(['>=', 'i.created_at', strtotime($search->starting_date)])
                // ->andFilterWhere(['<=', 'i.created_at', (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->andFilterWhere(['between', 'i.created_at', strtotime($search->starting_date), (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->orderBy(['i.created_at' => SORT_ASC])->all();

            // $models = Invoice::find()->alias('i')
            //     ->joinWith(['branch', 'payments', 'appointments'])
            //     ->where($mainCondition)
            //     ->andFilterWhere([
            //         'between',
            //         'i.created_at',
            //         strtotime($search->starting_date . ' 00:00:00'), // Start of the starting date (midnight)
            //         strtotime($search->ending_date . ' 23:59:59')  // End of the ending date (just before midnight of the next day)
            //     ])
            //     ->orderBy(['i.created_at' => SORT_ASC])
            //     ->all();


            foreach ($models as $model) {
                $data['paid'] += $model->paid;
                $data['balance'] += $model->balance;
                $data['vat'] += $model->vat;
                $data['total'] += $model->total;

                foreach ($model->payments as $item) {
                    $data['payments'][$item->payment_method] += $item->amount_paid;
                }

                if (!empty($model->appointments)) {
                    $doctor = $model->appointments[0]->doctor;

                    if (!isset($data['doctors'][$doctor->id])) {
                        $data['doctors'][$doctor->id] = [
                            'name' => $doctor->name,
                            'paid' => 0,
                            'payments' => [
                                InvoicePayment::METHOD_CASH => 0,
                                InvoicePayment::METHOD_CHEQUE => 0,
                                InvoicePayment::METHOD_DEBIT_CARD => 0,
                                InvoicePayment::METHOD_CREDIT_CARD => 0,
                                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                                InvoicePayment::METHOD_BENEFIT_PAY => 0,
                            ],
                            'balance' => 0,
                            'vat' => 0,
                            'total' => 0,
                        ];
                    }

                    $data['doctors'][$doctor->id]['paid'] += $model->paid;
                    $data['doctors'][$doctor->id]['balance'] += $model->balance;
                    $data['doctors'][$doctor->id]['vat'] += $model->vat;
                    $data['doctors'][$doctor->id]['total'] += $model->total;

                    foreach ($model->payments as $item) {
                        $data['doctors'][$doctor->id]['payments'][$item->payment_method] += $item->amount_paid;
                    }
                }
            }
        }
        // echo "<pre>";print_r($data);die;
        return $this->render('invoices', [
            'data' => $data,
            'search' => $search,
        ]);
    }
    public function actionPdfByDoctor()
    {
        $search = new ReportForm();
        $arr = ['ReportForm' => Yii::$app->request->queryParams];
        // print_r($arr);die;
        $search->load($arr);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $data = [
            'paid' => 0,
            'payments' => [
                InvoicePayment::METHOD_CASH => 0,
                InvoicePayment::METHOD_CHEQUE => 0,
                InvoicePayment::METHOD_DEBIT_CARD => 0,
                InvoicePayment::METHOD_CREDIT_CARD => 0,
                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                InvoicePayment::METHOD_BENEFIT_PAY => 0,
            ],
            'balance' => 0,
            'vat' => 0,
            'total' => 0,
            'doctors' => [],
        ];

        if ($search->validate()) {
            $mainCondition = ['b.clinic_id' => $clinicId, 'i.status' => Invoice::STATUS_ACTIVE];
            if (!empty($search->branch_id)) {
                $mainCondition['b.id'] = $search->branch_id;
            }
            $models = Invoice::find()->alias('i')->joinWith(['branch', 'payments', 'appointments'])->where($mainCondition)
                // ->andFilterWhere(['>=', 'i.created_at', strtotime($search->starting_date)])
                // ->andFilterWhere(['<=', 'i.created_at', (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->andFilterWhere(['between', 'i.created_at', strtotime($search->starting_date), (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->orderBy(['i.created_at' => SORT_ASC])->all();

            foreach ($models as $model) {
                $data['paid'] += $model->paid;
                $data['balance'] += $model->balance;
                $data['vat'] += $model->vat;
                $data['total'] += $model->total;

                foreach ($model->payments as $item) {
                    $data['payments'][$item->payment_method] += $item->amount_paid;
                }

                if (!empty($model->appointments)) {
                    $doctor = $model->appointments[0]->doctor;

                    if (!isset($data['doctors'][$doctor->id])) {
                        $data['doctors'][$doctor->id] = [
                            'name' => $doctor->name,
                            'paid' => 0,
                            'payments' => [
                                InvoicePayment::METHOD_CASH => 0,
                                InvoicePayment::METHOD_CHEQUE => 0,
                                InvoicePayment::METHOD_DEBIT_CARD => 0,
                                InvoicePayment::METHOD_CREDIT_CARD => 0,
                                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                                InvoicePayment::METHOD_BENEFIT_PAY => 0,
                            ],
                            'balance' => 0,
                            'vat' => 0,
                            'total' => 0,
                        ];
                    }

                    $data['doctors'][$doctor->id]['paid'] += $model->paid;
                    $data['doctors'][$doctor->id]['balance'] += $model->balance;
                    $data['doctors'][$doctor->id]['vat'] += $model->vat;
                    $data['doctors'][$doctor->id]['total'] += $model->total;

                    foreach ($model->payments as $item) {
                        $data['doctors'][$doctor->id]['payments'][$item->payment_method] += $item->amount_paid;
                    }
                }
            }
        }

        $content = $this->renderPartial('pdfByDoctor', [
            'data' => $data,
            'search' => $search,
        ]);

        $PDF = new \Mpdf\Mpdf([
            'mode' => '',
            'format' => 'A4',
            'orientation' => 'P',
            'destination' => 'I',
            'dpi' => 300,
            'tempDir' => Yii::getAlias('@clinic/documents/temp'),
        ]);

        $stylesheet = file_get_contents(Yii::getAlias('@clinic/web/css/pdf.css'));

        $vatAccount = '';
        if (!empty($vatAccount)) {
            $vatAccount = \yii\helpers\Html::tag('div', "VAT ACCOUNT # $vatAccount", ['class' => 'salamat-footer-vat']);
        }

        $PDF->SetTitle("PDF");
        $PDF->SetHTMLFooter(
            $vatAccount .
            \yii\helpers\Html::tag('div', \yii\helpers\Html::img(Yii::getAlias('@web/img/logo2.png')), [
                'class' => 'salamat-footer-logo',
            ])
        );
        $PDF->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $PDF->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);

        // echo "<pre>";print_r($model->invoiceID);die;

        return $PDF->Output("INVOICE }.pdf", \Mpdf\Output\Destination::INLINE);
    }
    public function actionExcelByDoctor()
    {

        $search = new ReportForm();
        $arr = ['ReportForm' => Yii::$app->request->queryParams];
        // print_r($arr);die;
        $search->load($arr);
        $clinicId = Yii::$app->user->identity->active_clinic;

        $data = [
            'paid' => 0,
            'payments' => [
                InvoicePayment::METHOD_CASH => 0,
                InvoicePayment::METHOD_CHEQUE => 0,
                InvoicePayment::METHOD_DEBIT_CARD => 0,
                InvoicePayment::METHOD_CREDIT_CARD => 0,
                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                InvoicePayment::METHOD_BENEFIT_PAY => 0,
            ],
            'balance' => 0,
            'vat' => 0,
            'total' => 0,
            'doctors' => [],
        ];

        if ($search->validate()) {
            $mainCondition = ['b.clinic_id' => $clinicId, 'i.status' => Invoice::STATUS_ACTIVE];
            if (!empty($search->branch_id)) {
                $mainCondition['b.id'] = $search->branch_id;
            }
            $models = Invoice::find()->alias('i')->joinWith(['branch', 'payments', 'appointments'])->where($mainCondition)
                // ->andFilterWhere(['>=', 'i.created_at', strtotime($search->starting_date)])
                // ->andFilterWhere(['<=', 'i.created_at', (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->andFilterWhere(['between', 'i.created_at', strtotime($search->starting_date), (strtotime("+1 day", strtotime($search->ending_date)) - 1)])
                ->orderBy(['i.created_at' => SORT_ASC])->all();

            foreach ($models as $model) {
                $data['paid'] += $model->paid;
                $data['balance'] += $model->balance;
                $data['vat'] += $model->vat;
                $data['total'] += $model->total;

                foreach ($model->payments as $item) {
                    $data['payments'][$item->payment_method] += $item->amount_paid;
                }

                if (!empty($model->appointments)) {
                    $doctor = $model->appointments[0]->doctor;

                    if (!isset($data['doctors'][$doctor->id])) {
                        $data['doctors'][$doctor->id] = [
                            'name' => $doctor->name,
                            'paid' => 0,
                            'payments' => [
                                InvoicePayment::METHOD_CASH => 0,
                                InvoicePayment::METHOD_CHEQUE => 0,
                                InvoicePayment::METHOD_DEBIT_CARD => 0,
                                InvoicePayment::METHOD_CREDIT_CARD => 0,
                                InvoicePayment::METHOD_BANK_TRANSFER => 0,
                                InvoicePayment::METHOD_BENEFIT_PAY => 0,
                            ],
                            'balance' => 0,
                            'vat' => 0,
                            'total' => 0,
                        ];
                    }

                    $data['doctors'][$doctor->id]['paid'] += $model->paid;
                    $data['doctors'][$doctor->id]['balance'] += $model->balance;
                    $data['doctors'][$doctor->id]['vat'] += $model->vat;
                    $data['doctors'][$doctor->id]['total'] += $model->total;

                    foreach ($model->payments as $item) {
                        $data['doctors'][$doctor->id]['payments'][$item->payment_method] += $item->amount_paid;
                    }
                }
            }
        }



        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();

        // Create a worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Create a worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $columnHeaders = ['Doctor', 'Paid', 'Balance', 'VAT', 'Total'];
        $worksheet->fromArray($columnHeaders, null, 'A1');

        // Initialize row counter
        $row = 2;

        // Write data to the worksheet
        foreach ($data['doctors'] as $doctorId => $doctorData) {
            $rowData = [
                $doctorData['name'],
                $doctorData['paid'],
                $doctorData['balance'],
                $doctorData['vat'],
                $doctorData['total'],
            ];
            $worksheet->fromArray($rowData, null, 'A' . $row);
            $row++;
        }

        // Create a writer to save the spreadsheet to a file
        $writer = new Xlsx($spreadsheet);
        // Add some data to the worksheet

        // Create a writer to save the spreadsheet to a file
        //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = "Invoice_" . Yii::getAlias('@web/export.xlsx');
        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/vnd.ms-excel');
        $headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $headers->set('Cache-Control: max-age=0');

        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();

        return $content;


    }


    public function actionPdf()
    {
        $model = $this->findModel();


        // echo "<pre>";print_r("hello world");die();

        $content = $this->renderPartial('pdf', [
            'model' => $model,
        ]);

        $PDF = new \Mpdf\Mpdf([
            'mode' => '',
            'format' => 'A4',
            'orientation' => 'P',
            'destination' => 'I',
            'dpi' => 300,
            'tempDir' => Yii::getAlias('@clinic/documents/temp'),
        ]);

        $stylesheet = file_get_contents(Yii::getAlias('@clinic/web/css/pdf.css'));

        $vatAccount = '';
        if (!empty($vatAccount)) {
            $vatAccount = \yii\helpers\Html::tag('div', "VAT ACCOUNT # $vatAccount", ['class' => 'salamat-footer-vat']);
        }

        $PDF->SetTitle("PDF");
        $PDF->SetHTMLFooter(
            $vatAccount .
            \yii\helpers\Html::tag('div', \yii\helpers\Html::img(Yii::getAlias('@web/img/logo2.png')), [
                'class' => 'salamat-footer-logo',
            ])
        );
        $PDF->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $PDF->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);

        // echo "<pre>";print_r($model->invoiceID);die;

        return $PDF->Output("INVOICE }.pdf", \Mpdf\Output\Destination::INLINE);
    }

    protected function findModel()
    {
        if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic])->all()) !== null) {
            // if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }



}
