<?php

namespace clinic\controllers\patients;

use Yii;
use clinic\models\Diagnosis;
use clinic\models\SickLeave;
use clinic\models\SickLeaveSearch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SickLeavesController implements the CRUD actions for SickLeave model.
 */
class SickLeavesController extends Controller
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
                    'roles' => ['View sick leaves'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Lists all SickLeave models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new SickLeaveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SickLeave model.
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
        
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        
        $stylesheet = file_get_contents(Yii::getAlias('@clinic/web/css/pdf.css'));
        
        $PDF->SetTitle("{$model->patient->name} ".date('Y-m-d', $model->created_at));
        $PDF->SetHTMLFooter(\yii\helpers\Html::tag('div', \yii\helpers\Html::img(Yii::getAlias('@web/img/logo2.png')), [
            'class' => 'salamat-footer-logo',
        ]));
        $PDF->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $PDF->WriteHTML($content,\Mpdf\HTMLParserMode::HTML_BODY);

        return $PDF->Output("{$model->patient->name} ".date('Y-m-d', $model->created_at).".pdf", \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Creates a new SickLeave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $diagnosis = Diagnosis::find()->alias('dg')->joinWith(['branch b'])->where(['dg.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one();

        if ($diagnosis === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if ($diagnosis->sickLeave !== null) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Selected diagnosis already has a sick leave"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        $date24 = strtotime('+1 day', $diagnosis->created_at);
        if (time() > $date24) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "This action cannot be performed after passing 24 hours"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        if (!Yii::$app->user->identity->isDoctor || $diagnosis->created_by != Yii::$app->user->identity->id) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only diagnosis doctor can perform this action"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        $model = new SickLeave([
            'patient_id' => $diagnosis->patient_id,
            'diagnosis_id' => $diagnosis->id,
            'commencing_on' => date('Y-m-d'),
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Sick leave created"));
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't create sick leave"));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'diagnosis' => $diagnosis,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $diagnosis = $model->diagnosis;

        $date24 = strtotime('+1 day', $diagnosis->created_at);
        if (time() > $date24) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "This action cannot be performed after passing 24 hours"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        if (!Yii::$app->user->identity->isDoctor || $model->created_by != Yii::$app->user->identity->id) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only diagnosis doctor can perform this action"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Sick leave updated"));
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't update sick leave"));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'diagnosis' => $diagnosis,
        ]);
    }

    /**
     * Finds the SickLeave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SickLeave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SickLeave::find()->alias('sl')->joinWith('branch')->where(['sl.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
