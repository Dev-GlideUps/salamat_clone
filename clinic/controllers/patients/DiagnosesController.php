<?php

namespace clinic\controllers\patients;

use Yii;
use clinic\models\FavoriteDiagnosis;
use clinic\models\FavoriteDiagnosisSearch;
use clinic\models\Patient;
use clinic\models\Diagnosis;
use clinic\models\DiagnosisSearch;
use admin\models\DiagnosisCode;
use clinic\models\DoctorClinicBranch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DiagnosesController implements the CRUD actions for Diagnosis model.
 */
class DiagnosesController extends Controller
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
                    'roles' => ['View diagnoses'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'search-icd10' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Diagnosis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new DiagnosisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Diagnosis model.
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
     * Creates a new Diagnosis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $patient = Patient::find()->alias('p')->joinWith('clinicPatient')->where(['p.id' => $id])->one();
        $user = Yii::$app->user->identity;

        if ($patient === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if (!$user->isDoctor) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', 'Only a doctor can perform this action'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        $branches = DoctorClinicBranch::find()->joinWith('branch b')->where(['doctor_id' => $user->doctor->id, 'b.clinic_id' => $user->active_clinic])->select('b.name')->indexBy('b.id')->column();
        
        $favDiagnoses = FavoriteDiagnosis::find()->alias('dg')->joinWith('clinic c')->where(['c.id' => Yii::$app->user->identity->active_clinic])->all();

        $model = new Diagnosis([
            'patient_id' => $patient->id,
            'branch_id' => array_key_first($branches),
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if (!isset($branches[$model->branch_id])) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', 'Invalid branch'));
            } elseif($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', 'Diagnosis added successfully'));
                if (isset(Yii::$app->request->post()['save_and_new'])) {
                    return $this->redirect(['create', 'id' => $patient->id]);
                }
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'patient' => $patient,
            'branches' => $branches,
            'favDiagnoses' => $favDiagnoses,
        ]);
    }

    /**
     * Updates an existing Diagnosis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $patient = $model->patient;
        $user = Yii::$app->user->identity;

        if (!$user->isDoctor) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', 'Only a doctor can perform this action'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        if ($user->id != $model->created_by) {
            Yii::$app->session->setFlash('error', Yii::t('patient', 'You cannot update this diagnosis'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        if (time() > strtotime('+1 day', $model->created_at)) {
            Yii::$app->session->setFlash('error', Yii::t('patient', 'Cannot update diagnosis after 24 hours'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        $branches = DoctorClinicBranch::find()->joinWith('branch')->where(['doctor_id' => $user->doctor->id, 'b.clinic_id' => $user->active_clinic])->select('b.name')->indexBy('b.id')->column();

        $favDiagnoses = FavoriteDiagnosis::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if (!isset($branches[$model->branch_id])) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', 'Invalid branch'));
            } elseif($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', 'Diagnosis updated successfully'));
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'patient' => $patient,
            'branches' => $branches,
            'favDiagnoses' => $favDiagnoses,
        ]);
    }
    
    public function actionSearchIcd10()
    {
        if (!Yii::$app->request->isAjax) {
            $this->redirect(['index']);
        }
        
        try {
            $search = Yii::$app->request->post('search');

            $diagnoses = DiagnosisCode::find()->where(['OR',
                ['like', 'code', $search],
                ['like', 'description', $search],
            ])->orderBy(['code' => SORT_ASC])->limit(64)->all();

            return $this->renderPartial('_icd_10_search', [
                'diagnoses' => $diagnoses,
            ]);
        } catch (\Exception $e) {
            return \yii\helpers\Html::tag('div', $e->getMessage(), ['class' => 'mdt-subtitle-2 text-secondary']);
        }
    }

    /**
     * Finds the Diagnosis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Diagnosis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagnosis::find()->alias('dg')->joinWith('branch')->where(['dg.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
