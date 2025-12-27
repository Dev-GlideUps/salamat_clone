<?php

namespace clinic\controllers\clinic;

use app\models\PatientConsent;
use Yii;
use patient\models\Attachment;
use clinic\models\Patient;
use clinic\models\PatientSearch;
use clinic\models\ClinicPatient;
use clinic\models\Appointment;
use clinic\models\Invoice;
use clinic\models\Diagnosis;
use clinic\models\PatientExamNotes;
use clinic\models\Prescription;
use clinic\controllers\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PatientsController implements the CRUD actions for Patient model.
 */
class PatientsController extends Controller
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
                    'actions' => ['photo', 'thumbnail','sticker'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['index', 'export', 'view'],
                    'allow' => true,
                    'roles' => ['View patients'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['Add patients'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['Update patients'],
                ],
            ],
        ];

        return $behaviors;
    }
    /**
     * Lists all Patient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new PatientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionExport()
    {
        $patients = Patient::find()->alias('p')->joinWith(['clinicPatient cp'])->all();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $country = new \common\models\Country();

        $i = 1;
        $sheet->setCellValue("A$i", 'Profile ID');
        $sheet->setCellValue("B$i", 'Patient Name');
        $sheet->setCellValue("C$i", 'CPR/SSN');
        $sheet->setCellValue("D$i", 'Nationality');
        $sheet->setCellValue("E$i", 'Phone');
        $sheet->setCellValue("F$i", 'DOB');

        foreach ($patients as $item) {
            $nationality = '';
            if ($item->nationality !== null) {
                $nationality = $country->countriesList[$item->nationality];
            }
            $dob = '';
            if ($item->dob !== null) {
                $dob = Yii::$app->formatter->asDate($item->dob);
            }

            $i++;
            $sheet->setCellValue("A$i", $item->clinicPatient->profile_ref);
            $sheet->setCellValue("B$i", $item->name);
            $sheet->setCellValue("C$i", $item->cpr);
            $sheet->setCellValue("D$i", $nationality);
            $sheet->setCellValue("E$i", $item->phone);
            $sheet->setCellValue("F$i", $dob);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = Yii::getAlias('@web/export.xlsx');
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
    }

    /**
     * Displays a single Patient model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->pushNavHistory();

        $model = $this->findModel($id);
        $appointments = Appointment::find()->alias('app')->joinWith('branch')->with(['doctor'])->where(['app.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->limit(5)->orderBy(['app.date' => SORT_DESC, 'app.time' => SORT_DESC])->all();
        $invoices = Invoice::find()->alias('i')->joinWith('branch')->where(['i.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->limit(5)->orderBy(['i.created_at' => SORT_DESC])->all();
        $diagnosis = Diagnosis::find()->alias('dg')->joinWith(['branch', 'doctor'])->where(['dg.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->limit(16)->orderBy(['dg.created_at' => SORT_DESC])->all();
        $examinationNotes = PatientExamNotes::find()->alias('ex')->joinWith(['branch', 'doctor'])->where(['ex.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->limit(16)->orderBy(['ex.created_at' => SORT_DESC])->all();
        $prescriptions = Prescription::find()->alias('pr')->joinWith(['branch', 'doctor', 'diagnosis', 'items'])->where(['pr.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->limit(16)->orderBy(['pr.created_at' => SORT_DESC])->all();
        $attachments = Attachment::find()->alias('ath')->joinWith(['branch b', 'category cat', 'creator u1'])->where(['ath.patient_id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->orderBy(['ath.created_at' => SORT_DESC])->all();
        $patientConsent = PatientConsent::find()->where([
            'patient_id' => $id
        ]);

        $patientConsentProvider = new ActiveDataProvider([
            'query' => $patientConsent,
        ]);


        return $this->render('view', [
            'model' => $model,
            'appointments' => $appointments,
            'invoices' => $invoices,
            'diagnosis' => $diagnosis,
            'examinationNotes' => $examinationNotes,
            'prescriptions' => $prescriptions,
            'attachments' => $attachments,
            'patientConsentProvider' => $patientConsentProvider
        ]);
    }

    public function actionSticker ($id) {
        $model = $this->findModel($id);
        $stickerWidth = 100; // Adjust as needed
        $stickerHeight = 40; // Adjust as needed
        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
//            'format' => 'A4',
            'format' => [$stickerWidth, $stickerHeight],
            'orientation' => 'P',
            'destination' => 'I',
            'dpi' => 300,
            'tempDir' => Yii::getAlias('@clinic/documents/temp')
        ]);
        $content = $this->renderPartial('sticker', [
            'model' => $model,
        ]);

        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->WriteHTML($content);

        // Output the PDF
        return $PDF->Output('output.pdf', \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($cpr, $nationality)
    {
        $cpr = trim($cpr);
        $nationality = trim($nationality);
        $clinicPatient = new ClinicPatient(['clinic_id' => Yii::$app->user->identity->active_clinic]);

        if (($model = Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality])) !== null) {
            $clinicPatient->patient_id = $model->id;

            if ($clinicPatient->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Patient added successfully"));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', $clinicPatient->getErrorSummary(true)[0]);
                return $this->navHistoryBack(['index']);
            }
        }

        $model = new Patient(['cpr' => $cpr, 'nationality' => $nationality, 'phone_line' => '973']);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload() && $model->save()) {
            $clinicPatient->load(Yii::$app->request->post());
            $clinicPatient->patient_id = $model->id;

            if ($clinicPatient->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Patient added successfully"));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'clinicPatient' => $clinicPatient,
        ]);
    }

    public function actionPhoto($file)
    {
        $imgFullPath = \Yii::getAlias("@patient/documents/patients/photo/$file");
        if (!file_exists($imgFullPath)) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->getHeaders()
            ->set('Content-Type', mime_content_type($imgFullPath))
            ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
            ->set('Pragma', 'private')
            ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
            ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($imgFullPath)));

        $response->content = file_get_contents($imgFullPath);
        return $response->send();
    }

    public function actionThumbnail($file)
    {
        $imgFullPath = \Yii::getAlias("@patient/documents/patients/photo/thumbs/$file");
        if (!file_exists($imgFullPath)) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->getHeaders()
            ->set('Content-Type', mime_content_type($imgFullPath))
            ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
            ->set('Pragma', 'private')
            ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
            ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($imgFullPath)));

        $response->content = file_get_contents($imgFullPath);
        return $response->send();
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (empty($model->phone_line)) {
            $model->phone_line = '973';
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload() && $model->save()) {
            $model->clinicPatient->load(Yii::$app->request->post());
            if ($model->clinicPatient->save(true, ['profile_ref'])) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Patient information updated successfully"));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Patient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Patient::find()->alias('p')->joinWith('clinicPatient')->where(['p.id' => $id])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
