<?php

namespace clinic\controllers;


use clinic\models\Appointment;
use clinic\models\Branch;
use clinic\models\Doctor;
use clinic\models\ExcelUpload;
use clinic\models\Patient;
use Yii;
use app\models\ConsentForm;
use app\models\ConsentFormSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ConsentFormController implements the CRUD actions for ConsentForm model.
 */
class ConsentFormController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ConsentForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConsentFormSearch([
            'clinic_id'=>Yii::$app->user->identity->active_clinic
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConsentForm model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ConsentForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConsentForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConsentForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ConsentForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExcelUpload() {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $model = new ExcelUpload();
        if ($model->load(Yii::$app->request->post())) {
            $excel = UploadedFile::getInstance($model, 'file');
            if ($excel) {
                $model->file = 'products-import-' . time() . '.' . $excel->extension;
                $upload_path = Yii::$app->basePath . '/web/img/';
                $path = $upload_path . $model->file;
                $excel->saveAs($path);
                $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                // $objPHPExcel = \PHPExcel_IOFactory::load($path);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                $transaction = Yii::$app->db->beginTransaction();
                try {
//                    var_dump($sheetData);
//                    var_dump(count($sheetData));
//                    exit;
                    for ($i = 2; $i <= count($sheetData); $i++) {
                        if ($sheetData[$i]['A']) {
                            $appointmentDate = trim(strtolower($sheetData[$i]['A']));
                            $patientCpr = trim(strtolower($sheetData[$i]['B']));
                            $doctorName = trim(strtolower($sheetData[$i]['E']));
                            $appointmentName = trim(strtolower($sheetData[$i]['F']));
                            $price = trim(strtolower($sheetData[$i]['I']));
                            $clinicId = 9;

                            $clinicBranch = Branch::find()->where(['clinic_id' => $clinicId])->one();

                            //find patient_id
                            //doctor_id
                            $patientModel = Patient::find()->where(['cpr' => $patientCpr])->one();
                            $doctorModel = Doctor::find()
                                ->where([
                                    'id' => 74
                                ])
                               // ->where(['=', new \yii\db\Expression('LOWER(name)'), strtolower($doctorName)])
                                ->one();

        

                            if ($patientModel) {

                                $timestamp = strtotime($appointmentDate);
                                $formattedDate = date('Y-m-d', $timestamp);

                                $randomHour = rand(11, 23);
                                $randomMinute = rand(0, 40);
                                $randomStartTime = sprintf('%02d:%02d:%02d', $randomHour, $randomMinute, '00');
                                $randomEndTime = sprintf('%02d:%02d:%02d', $randomHour, $randomMinute + 15, '00');

                                $createAt = $appointmentDate . ' ' . $randomStartTime;
                                $unixTimestamp = strtotime($createAt);

                                $appointmentModel = new Appointment();
                                $appointmentModel->patient_id = $patientModel->id;
                                $appointmentModel->doctor_id = $doctorModel->id;
                                $appointmentModel->branch_id = $clinicBranch->id;
                                $appointmentModel->status = 3;
                                $appointmentModel->date = $formattedDate;
                                $appointmentModel->time = date('g:i A', strtotime("{$formattedDate} {$randomStartTime}"));
//                           print_r($randomStartTime);
                                $appointmentModel->service_id = $doctorModel->id . '-' . $clinicBranch->id;
                                $appointmentModel->duration = 15;
                                $appointmentModel->doctor_branch = $doctorModel->id . '-' . $clinicBranch->id;
                                $appointmentModel->end_time = date('g:i A', strtotime("+15 minutes", strtotime("{$formattedDate} {$randomStartTime}")));
                                $appointmentModel->price = $price;
                                $appointmentModel->service = json_encode(['title' => $appointmentName, 'title_alt' => '']);
                                $appointmentModel->created_at = $unixTimestamp;
                                $appointmentModel->updated_at = $unixTimestamp;
                                if ($appointmentModel->save()) {

                                } else {
                                    var_dump($appointmentModel->errors);
//                                    exit;


                                }

                            } else {
                                var_dump('patient not found');
                            }
                        }
                    }
                    $transaction->commit();
                }catch (\Exception $e) {
                    print_r($e);
                    exit;
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    print_r($e);
                    exit;
                    $transaction->rollBack();
                    throw $e;
                }

//                 var_dump($sheetData); exit;
            }
        }
        return $this->render('excel-import', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the ConsentForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConsentForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConsentForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
