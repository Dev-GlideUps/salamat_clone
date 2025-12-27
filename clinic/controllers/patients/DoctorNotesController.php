<?php

namespace clinic\controllers\patients;

use Yii;
use clinic\models\PatientExamNotes;
use clinic\models\PatientExamNotesSearch;
use clinic\models\Patient;
use clinic\models\DoctorClinicBranch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoctorNotesController implements the CRUD actions for PatientExamNotes model.
 */
class DoctorNotesController extends Controller
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
        
        return $behaviors;
    }

    /**
     * Lists all PatientExamNotes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new PatientExamNotesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PatientExamNotes model.
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
     * Creates a new PatientExamNotes model.
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

        $model = new PatientExamNotes([
            'patient_id' => $patient->id,
            'branch_id' => array_key_first($branches),
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if (!isset($branches[$model->branch_id])) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', 'Invalid branch'));
            } elseif($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', 'Examination notes added successfully'));
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'patient' => $patient,
            'branches' => $branches,
        ]);
    }

    /**
     * Updates an existing PatientExamNotes model.
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
            Yii::$app->session->setFlash('error', Yii::t('general', 'You cannot update this record'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        if (time() > strtotime('+1 day', $model->created_at)) {
            Yii::$app->session->setFlash('error', Yii::t('patient', 'Cannot update notes after 24 hours'));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        $branches = DoctorClinicBranch::find()->joinWith('branch')->where(['doctor_id' => $user->doctor->id, 'b.clinic_id' => $user->active_clinic])->select('b.name')->indexBy('b.id')->column();

        if ($model->load(Yii::$app->request->post())) {
            if (!isset($branches[$model->branch_id])) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', 'Invalid branch'));
            } elseif($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', 'Doctor notes updated successfully'));
                return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'patient' => $patient,
            'branches' => $branches,
        ]);
    }

    /**
     * Finds the PatientExamNotes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PatientExamNotes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PatientExamNotes::find()->alias('ex')->joinWith('branch')->where(['ex.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
