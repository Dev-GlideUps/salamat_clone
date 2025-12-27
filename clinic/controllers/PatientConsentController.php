<?php

namespace clinic\controllers;

use app\models\ConsentForm;
use clinic\models\Patient;
use Yii;
use app\models\PatientConsent;
use app\models\PatientConsentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PatientConsentController implements the CRUD actions for PatientConsent model.
 */
class PatientConsentController extends Controller
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
     * Lists all PatientConsent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PatientConsentSearch(
                [
                    'clinic_id'=>Yii::$app->user->identity->active_clinic

                ]
        );
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PatientConsent model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PatientConsent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new PatientConsent();
        $consentModel = ConsentForm::find()->where([
            'id' => $id
        ])->one();

        if ($model->load(Yii::$app->request->post())) {

            if (isset($model->signature)) {
                $base64Data = $model->signature;
                $base64Data = explode(',',$base64Data);
                $data = $base64Data;
                $data = base64_decode($data[1]);
                $imageName = time() . '.svg';
                $savePath = Yii::$app->basePath . '/web/img/'.$imageName;
                file_put_contents($savePath, $data);
                $model->signature = $imageName;
            }
            if (isset($model->doctor_signature)) {
                $base64Data = $model->doctor_signature;
                $base64Data = explode(',',$base64Data);
                $data = $base64Data;
                $data = base64_decode($data[1]);
                $imageName = time() . 'doc.svg';
                $savePath = Yii::$app->basePath . '/web/img/'.$imageName;
                file_put_contents($savePath, $data);
                $model->doctor_signature = $imageName;
            }
            if ($model->save()) {

            } else {
                return json_encode($model->errors);
            }
            return $this->redirect(['index']);
        }

        $model->consent_id = $id;
        return $this->render('create', [
            'model' => $model,
            'consentModel' => $consentModel
        ]);
    }

    /**
     * Updates an existing PatientConsent model.
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

    public function actionConsentPdf ($id) {
        $patientConsentModel = PatientConsent::find()->where(['id' => $id])->one();
        $consentModel = ConsentForm::findOne($patientConsentModel->consent_id);
        $patientModel = Patient::findOne($patientConsentModel->patient_id);

        if ($consentModel->template_type === 3) {
            $content = $this->renderPartial('arabic-template', [
                'model' => $patientConsentModel,
                'consentForm' => $consentModel,
                'patient' => $patientModel,
            ]);
        } elseif ($consentModel->template_type === 4) {
            $content = $this->renderPartial('top-layout', [
                'model' => $patientConsentModel,
                'consentForm' => $consentModel,
                'patient' => $patientModel,
            ]);
        } else {
            $content = $this->renderPartial('consent-default-form', [
                'model' => $patientConsentModel,
                'consentForm' => $consentModel,
                'patient' => $patientModel,
            ]);
        }

        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'destination' => 'I',
            'dpi' => 300,
            'tempDir' => Yii::getAlias('@clinic/documents/temp')
        ]);


        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->WriteHTML($content);

        // Output the PDF
        return $PDF->Output('output.pdf', \Mpdf\Output\Destination::INLINE);

    }

    /**
     * Deletes an existing PatientConsent model.
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

    /**
     * Finds the PatientConsent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PatientConsent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PatientConsent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
