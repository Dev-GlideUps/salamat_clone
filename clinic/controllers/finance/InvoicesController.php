<?php

namespace clinic\controllers\finance;

use Yii;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;

use clinic\models\Branch;
use clinic\models\Appointment;
use clinic\models\Invoice;
use clinic\models\InvoiceSearch;
use clinic\models\InvoiceItem;
use clinic\models\Patient;
use clinic\models\ClinicPatient;
use insurance\models\Company;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;	

/**
 * InvoicesController implements the CRUD actions for Invoice model.
 */
class InvoicesController extends Controller
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
                    'actions' => ['index', 'view', 'pdf', 'insurance-claims', 'pdf-view', 'pdf-all','excel-all'],
                    'allow' => true,
                    'roles' => ['View invoices'],
                ],
                [
                    'actions' => ['create', 'appointment-invoice', 'invoice-preview'],
                    'allow' => true,
                    'roles' => ['Create invoices'],
                ],
                [
                    'actions' => ['cancel'],
                    'allow' => true,
                    'roles' => ['Admin'],
                ],
                [
                    'actions' => ['update-patient-phone'],
                    'allow' => true,
                    'roles' => ['Update patients'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Invoice with insurance.
     * @return mixed
     */
    public function actionInsuranceClaims()
    {
        $this->pushNavHistory();

        $searchModel = new InvoiceSearch();
        $params = Yii::$app->request->queryParams;
        $params['InvoiceSearch']['has_insurance'] = true;
        $dataProvider = $searchModel->search($params);

        return $this->render('insurance-claims', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
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

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

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
        
        $vatAccount = $model->clinic->vat_account;
        if (!empty($vatAccount)) {
            $vatAccount = \yii\helpers\Html::tag('div', "VAT ACCOUNT # $vatAccount", ['class' => 'salamat-footer-vat']);
        }

        $PDF->SetTitle("INVOICE #{$model->invoiceID}");
        $PDF->SetHTMLFooter(
            $vatAccount.
            \yii\helpers\Html::tag('div', \yii\helpers\Html::img(Yii::getAlias('@web/img/logo2.png')), [
                'class' => 'salamat-footer-logo',
            ])
        );
        $PDF->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $PDF->WriteHTML($content,\Mpdf\HTMLParserMode::HTML_BODY);

        return $PDF->Output("INVOICE #{$model->invoiceID}.pdf", \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAppointmentInvoice($id)
    {
        $appointment = Appointment::find()->alias('app')->joinWith(['branch', 'patient'])->where(['app.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one();

        if ($appointment === null || !$appointment->canCreateInvoice) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if ($appointment->invoice !== null) {
            Yii::$app->session->setFlash('error', Yii::t('finance', "Selected appointment already has an invoice"));
            return $this->navHistoryBack(['view', 'id' => $appointment->invoice->id]);
        }

        $services = [
            $appointment->branch_id => $appointment->branch->services,
        ];

        $companies = Company::find()->select('name')->indexBy('id')->column();

        $model = new Invoice([
            'status' => Invoice::STATUS_ACTIVE,
            'branch_id' => $appointment->branch_id,
            'patient_id' => $appointment->patient_id,
            'max_appointments' => 1,
            'vat' => 0,
            'discount' => 0,
            'insurance_amount' => 0,
            'insurance_coverage' => 0,
            'insurance_mode' => Invoice::INSURANCE_PERCENT,
        ]);

        $items = [];
        $items[] = new InvoiceItem([
            'item' => $appointment->serviceTitle['title'],
            'amount' => $appointment->price,
            'discount_unit' => 'percent',
        ]);
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->branch_id = $appointment->branch_id;

            $items = [];
            $itemError = false;
            foreach (Yii::$app->request->post('InvoiceItem') as $i => $item) {
                $items[$i] = new InvoiceItem($item);
                $itemError = !$items[$i]->validate() || $itemError;
            }
            if (!$itemError) {
                $model->invoiceItems = $items;
                if ($model->save()) {
                    $appointment->updateAttributes(['invoice_id' => $model->id]);
                    Yii::$app->session->setFlash('success', Yii::t('finance', "New invoice created successfully"));
                    return $this->navHistoryBack(['view', 'id' => $model->id]);
                }
            }
            Yii::$app->session->setFlash('error', Yii::t('finance', "Couldn't create invoice"));
        }

        return $this->render('appointment_invoice', [
            'model' => $model,
            'appointment' => $appointment,
            'services' => $services,
            'insuranceCompanies' => $companies,
            'items' => $items,
        ]);
    }

    public function actionCreate($cpr, $nationality)
    {
        $cpr = trim($cpr);
		$nationality = trim($nationality);

        $branches = [];
        $services = [];
        foreach (Branch::find()->alias('b')->joinWith('services')->where(['clinic_id' => Yii::$app->user->identity->active_clinic])->all() as $item) {
            $branches[$item->id] = $item->name;
            $services[$item->id] = $item->services;
        }
		
        if (($patient = Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality])) === null) {
            $patient = new Patient(['cpr' => $cpr, 'nationality' => $nationality, 'phone_line' => '973']);
        }
        
        if (($clinicPatient = $patient->clinicPatient) === null) {
            $clinicPatient = new ClinicPatient();
        }

        $companies = Company::find()->select('name')->indexBy('id')->column();

        $model = new Invoice([
            'status' => Invoice::STATUS_ACTIVE,
            'branch_id' => array_key_first($branches),
            'max_appointments' => 1,
            'vat' => 0,
            'discount' => 0,
            'insurance_amount' => 0,
            'insurance_coverage' => 0,
            'insurance_mode' => Invoice::INSURANCE_PERCENT,
        ]);

        $items = [];
        $items[] = new InvoiceItem([
            'item' => '',
            'amount' => '',
            'discount_unit' => 'percent',
        ]);
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if (count($branches) == 1) {
                $model->branch_id = array_key_first($branches);
            }

            if ($patient->isNewRecord && (!$patient->load(Yii::$app->request->post()) || !$patient->save())) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create new petient"));
                goto CREATE_VIEW;
            }

            if ($patient->clinicPatient == null) {
                $clinicPatient->load(Yii::$app->request->post());
                $clinicPatient->clinic_id = Yii::$app->user->identity->active_clinic;
                $clinicPatient->patient_id = $patient->id;
                $clinicPatient->save();
            }
            
            $model->patient_id = $patient->id;

            $items = [];
            $itemError = false;
            foreach (Yii::$app->request->post('InvoiceItem') as $i => $item) {
                $items[$i] = new InvoiceItem($item);
                $itemError = !$items[$i]->validate() || $itemError;
            }
            if (!$itemError) {
                $model->invoiceItems = $items;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('finance', "New invoice created successfully"));
                    return $this->navHistoryBack(['view', 'id' => $model->id]);
                }
            }
            Yii::$app->session->setFlash('error', Yii::t('finance', "Couldn't create invoice"));
        }

        CREATE_VIEW:
        return $this->render('create', [
            'model' => $model,
            'patient' => $patient,
            'clinicPatient' => $clinicPatient,
            'branches' => $branches,
            'services' => $services,
            'insuranceCompanies' => $companies,
            'items' => $items,
        ]);
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
    
    public function actionInvoicePreview()
    {
        if (!Yii::$app->request->isAjax) {
            $this->redirect(['index']);
        }

        try {
            $model = new Invoice([
                'status' => Invoice::STATUS_ACTIVE,
                'vat' => 0,
                'discount' => 0,
                'total' => 0,
                'paid' => 0,
            ]);
            
            $items = [];
            $itemError = false;

            $model->load(Yii::$app->request->post());
            foreach (Yii::$app->request->post('InvoiceItem') as $i => $item) {
                $items[$i] = new InvoiceItem($item);
                $itemError = !$items[$i]->validate() || $itemError;
            }
            if (!$itemError) {
                $model->invoiceItems = $items;
            }

            return $this->renderPartial('_invoice_preview', [
                'model' => $model,
            ]);
        } catch (\Exception $e) {
            return $this->renderPartial('_ajax_error', ['message' => $e->getMessage()]);
        }
    }
    
    public function actionCancel($id)
    {
        $model = $this->findModel($id);

        if ($model->canUpdateInvoice) {
            $model->updateAttributes(['status' => Invoice::STATUS_CANCELED]);
            Yii::$app->session->setFlash('success', Yii::t('finance', "Invoice cancelled"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('finance', "This invoice cannot be cancelled"));
        }
        
        return $this->navHistoryBack(['view', 'id' => $model->id]);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments','payments'])->where(['i.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }

    public function actionPdfAll()
    {
        ini_set('pcre.backtrack_limit', '2000000');
        ini_set('pcre.recursion_limit', '2000000');
        $model = $this->findModelAll();

        



        $content = $this->renderPartial('pdfAll', [
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


public function actionExcelAll()
    {
    $formatter = Yii::$app->formatter;
        ini_set('pcre.backtrack_limit', '2000000');
        ini_set('pcre.recursion_limit', '2000000');
        $model = $this->findModelAll();

        // Create a new spreadsheet
$spreadsheet = new Spreadsheet();

// Create a worksheet
$worksheet = $spreadsheet->getActiveSheet();

// Create a worksheet
$worksheet = $spreadsheet->getActiveSheet();
$data=[];
// Set column headers

$columnHeaders = ['Created at','Invoice ID','Doctor Name','Patient Name','Items','Amount','Discount','Vat 2','Total'];
$worksheet->fromArray($columnHeaders, null, 'A1');

// Initialize row counter
$row = 2;
  	$total_sum=0;
        $vat_sum=0;
        
        
        foreach($model as $sum){
            $total_sum+=$sum->total;
            $vat_sum+=$sum->vat;
        }
        
        
        foreach ($model as $index => $check) {
            $subtotal = 0;
            $items = '';
            $ammount =[];
            $vat2=[];
            $discount_per=[];
            foreach ($check->invoiceItems as $itemIndex => $item) {
                $price = $formatter->asDecimal($item['amount'], 3);
                $subtotalAmount = $item['qty'] === null ? $item['amount'] : $item['qty'] * $item['amount'];
                $discount = 0;
                if (!empty($item['discount_unit'])) {
                    if ($item['discount_unit'] == 'percent') {
                        $discount = $subtotalAmount * ($item['discount_value'] / 100);
                        // $discount = $item['discount_value'];
                    } else {
                        $discount = $item['discount_value'];
                    }
                }
                $vat = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                $discount_per[$itemIndex] = $discount;
                $vat2[$itemIndex] = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                $subtotal += $subtotalAmount;
                $items .=  ' '.$item['item'];
                // $ammount .=  $item['qty'] === null ? $price : " $subtotalAmount  ";
                $ammount[]= $item['qty'] === null ? $price  : $item['qty'] * $price ;
                // echo "<pre>";print_r($item);
                // echo "<br><pre>";print_r($vat);

                  $rowData = [
        'created_at' => $formatter->asDate($check->created_at, 'long'),
        'invoiceID' => $check->invoiceID,
        'doctor_name' => count($check->appointments) > 0 ? $check->appointments[0]->doctor->name : '',
        'patient_name' => $check->patient->name,
        'items' => $items,
        'amount' => implode($ammount,"\n"),
        'discount_per' => implode(array_map(function ($discount) use ($formatter) {
            return $formatter->asDecimal($discount, 3);	
        }, $discount_per),"\n"),
        'vat2' => implode(array_map(function ($vat) use ($formatter) {
            return $formatter->asDecimal($vat, 3);
        }, $vat2),"\n"),
        'total' => $formatter->asDecimal($check->total, 3)
    ];
    

   $worksheet->fromArray($rowData, null, 'A' . $row);
   $worksheet->calculateColumnWidths();

    //original code...
    $titlecolwidth = $worksheet->getColumnDimension('B')->getWidth();
    $worksheet->getColumnDimension('B')->setAutoSize(false);
  $titlecolwidth = $worksheet->getColumnDimension('H')->getWidth();
    $worksheet->getColumnDimension('H')->setAutoSize(false);
    
    # Auto-fitting the 4th column of the worksheet


    $row++;
}
            }





// Create a writer to save the spreadsheet to a file
$writer = new Xlsx($spreadsheet);
// Add some data to the worksheet

// Create a writer to save the spreadsheet to a file
  //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = "Invoice_".Yii::getAlias('@web/export.xlsx');
        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'application/vnd.ms-excel');
        $headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $headers->set('Cache-Control: max-age=0');
        
        ob_start();
        $writer->save("php://output");
        $content = ob_get_contents();
        ob_clean();

        return $content;

        // echo "<pre>";print_r($model->invoiceID);die;

        
    }
    public function actionPdfView()
    {

        $this->pushNavHistory();

        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search_date(Yii::$app->request->queryParams);

        // echo "<pre>";print_r($dataProvider);die;

        return $this->render('pdfView', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModelAll()
    {
        $start_date = Yii::$app->request->queryParams['to'];
        $end_date = Yii::$app->request->queryParams['from'];
        $check = Yii::$app->request->queryParams['check'];
        $to = strtotime($start_date);
        $from = strtotime($end_date);
        // if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->one()) !== null) {
        if ($start_date != '' && $end_date != '' && $check == 4) {
            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere(['between', 'i.created_at', strtotime($end_date), (strtotime("+1 day", strtotime($start_date)) - 1)])->all()) !== null) {
                return $model;
                
            }
            // if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere([
            //     'AND',
            //     ['>=', 'i.created_at', $from],
            //     ['<', 'i.created_at', $to],
            // ])->all()) !== null) {
            //     return $model;
                
            // }
        } else if ($check == 2) {
            $lastDate = date('Y-m-d', strtotime("- 7 day"));
            $curDate = date('Y-m-d');
            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastDate)],
                ['<', 'i.created_at', strtotime($curDate)],
            ])->all()) !== null) {
                return $model;
            }
        } else if ($check == 1) {
            $lastDate = date('Y-m-d', strtotime("- 1 day"));
            $curDate = date('Y-m-d');
            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastDate)],
                ['<', 'i.created_at', strtotime($curDate)],
            ])->all()) !== null) {
                return $model;
            }
        }else if ($check == 3) {
            $lastDate = date('Y-m-d', strtotime("- 30 day"));
            $curDate = date('Y-m-d');
            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastDate)],
                ['<', 'i.created_at', strtotime($curDate)],
            ])->all()) !== null) {
                return $model;
            }
        }
        else if($check ==0){

            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->all()) !== null) {
                return $model;
            }
        }
        else {

            if (($model = Invoice::find()->alias('i')->joinWith(['clinic', 'branch', 'appointments'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic,'i.status' => Invoice::STATUS_ACTIVE])->all()) !== null) {
                return $model;
            }
        }


        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }


}
