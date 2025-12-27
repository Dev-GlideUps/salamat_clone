<?php

namespace admin\controllers\clinics;

use Yii;
use yii\helpers\ArrayHelper;
use admin\models\Doctor;
use admin\models\DoctorSearch;
use clinic\models\DoctorService;
use clinic\models\Clinic;
use clinic\models\DoctorClinicBranch;
use clinic\models\Speciality;
use common\models\WorkingHoursForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DoctorsController implements the CRUD actions for Doctor model.
 */
class DoctorsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // All actions
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-schedule' => ['POST'],
                    'add-service' => ['POST'],
                    'update-service' => ['POST'],
                    'delete-service' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Doctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $specialities = Speciality::find()->select('title')->indexBy('id')->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'specialities' => $specialities,
        ]);
    }

    /**
     * Displays a single Doctor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $doctor = $this->findModel($id);
        $serviceModel = new DoctorService();

        return $this->render('view', [
            'model' => $doctor,
            'serviceModel' => $serviceModel,
        ]);
    }
    
    public function actionAddService($id)
    {
        $doctor = $this->findModel($id);
        $branch = Yii::$app->request->post('DoctorService')['branch_id'];
        $schedule = DoctorClinicBranch::findOne(['doctor_id' => $id, 'branch_id' => $branch]);
        if ($schedule === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $model = new DoctorService(['doctor_id' => $id, 'branch_id' => $branch]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Doctor service added"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot add doctor service"));
        }
        return $this->redirect(['view', 'id' => $id, '#' => "branch-$branch"]);
    }

    public function actionUpdateService()
    {
        $id = Yii::$app->request->post('DoctorService')['id'];
        $model = DoctorService::findOne($id);
        $doctor = $this->findModel($model->doctor_id);
        $schedule = DoctorClinicBranch::findOne(['doctor_id' => $model->doctor_id, 'branch_id' => $model->branch_id]);
        if ($schedule === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Doctor service updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot update doctor service"));
        }
        return $this->redirect(['view', 'id' => $model->doctor_id, '#' => "branch-$model->branch_id"]);
    }

    public function actionDeleteService()
    {
        $id = Yii::$app->request->post('id');
        $model = DoctorService::findOne($id);
        $doctor = $this->findModel($model->doctor_id);
        $schedule = DoctorClinicBranch::findOne(['doctor_id' => $model->doctor_id, 'branch_id' => $model->branch_id]);
        if ($schedule === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Doctor service deleted"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot delete doctor service"));
        }
        return $this->redirect(['view', 'id' => $model->doctor_id, '#' => "branch-$model->branch_id"]);
    }

    public function actionPhoto($file)
    {
        $imgFullPath = \Yii::getAlias("@clinic/documents/doctors/photo/$file");
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
        $imgFullPath = \Yii::getAlias("@clinic/documents/doctors/photo/thumbs/$file");
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
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Doctor();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload()) {
            $model->languages = $model->languageArray;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', 'New doctor record created successfully'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't save doctor data"));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Doctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->languageArray = $model->languages;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload()) {
            $model->languages = $model->languageArray;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', 'Doctor record updated successfully'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't save doctor data"));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSchedule($doctor_id, $branch_id = null)
    {
        $model = new DoctorClinicBranch(['doctor_id' => $doctor_id, 'status' => DoctorClinicBranch::STATUS_ACTIVE]);

        $workingHours = [
            7 => null,
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
        ];

        $branches = [];

        if ($branch_id !== null) {
            $model = DoctorClinicBranch::findOne(['doctor_id' => $doctor_id, 'branch_id' => $branch_id]);
            if ($model === null) {
                throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
            }

            $workingHours = $model->workingHours;
            foreach ($model->weekDays as $dayNum => $dayStatus) {
                if ($workingHours[$dayNum] !== null) {
                    $model->weekDays[$dayNum] = true;
                    $tempModels = [
                        7 => [],
                        1 => [],
                        2 => [],
                        3 => [],
                        4 => [],
                        5 => [],
                        6 => [],
                    ];
                    foreach ($workingHours[$dayNum] as $item) {
                        $tempModels[$dayNum][] = new WorkingHoursForm($item);
                    }
                    $workingHours[$dayNum] = $tempModels[$dayNum];
                }
            }
        } else {
            $clinics = Clinic::find()->all();
            foreach ($clinics as $item) {
                $branches[$item->name] = [];
                foreach ($item->branches as $branch) {
                    $branches[$item->name][$branch->id] = "$branch->name ($item->name)";
                }
            }
        }
        $doctor = $this->findModel($doctor_id);

        if ($model->load(Yii::$app->request->post())) {
            $workingHoursPost = Yii::$app->request->post('WorkingHoursForm');
            $workingHoursError = null;
            
            if (!empty($workingHoursPost)) {
                foreach ($model->weekDays as $dayNum => $dayStatus) {
                    if ((int) $dayStatus) {
                        $workingHours[$dayNum] = [];
                        $hoursRow = ArrayHelper::getValue($workingHoursPost, $dayNum, []);
                        foreach ($hoursRow as $item) {
                            $workingHoursModel = new WorkingHoursForm($item);
                            $workingHoursModel->validate();
                            $errors = $workingHoursModel->firstErrors;
                            if (!empty($errors) && empty($workingHoursError)) {
                                $workingHoursError = reset($errors);
                            }
                            $workingHours[$dayNum][] = $workingHoursModel;
                        }
                    } else {
                        $workingHours[$dayNum] = null;
                    }
                }
            }
            
            if ($model->validate() && $workingHoursError == null) {
                $model->workingHours = $workingHours;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('clinic', 'Doctor schedule updated'));
                    return $this->redirect(['view', 'id' => $doctor_id]);
                }
            }

            Yii::$app->session->setFlash('error', $workingHoursError);
        }

        return $this->render('schedule', [
            'model' => $model,
            'branches' => $branches,
            'doctor' => $doctor,
            'workingHours' => $workingHours,
        ]);
    }
    
    public function actionDeleteSchedule()
    {
        $doctor_id = Yii::$app->request->post('doctor_id');
        $branch_id = Yii::$app->request->post('branch_id');

        if (($model = DoctorClinicBranch::findOne(['doctor_id' => $doctor_id, 'branch_id' => $branch_id])) !== null) {
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('clinic', 'Doctor schedule deleted'));
            return $this->redirect(['view', 'id' => $doctor_id]);
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }

    /**
     * Deletes an existing Doctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('clinic', 'Doctor record deleted'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
