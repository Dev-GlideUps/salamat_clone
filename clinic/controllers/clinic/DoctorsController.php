<?php

namespace clinic\controllers\clinic;

use Yii;
use yii\helpers\ArrayHelper;
use clinic\models\Branch;
use clinic\models\Doctor;
use clinic\models\DoctorSearch;
use clinic\models\DoctorClinicBranch;
use clinic\models\DoctorService;
use common\models\WorkingHoursForm;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => $this->_accessControl,
            'rules' => [
                [
                    'actions' => ['photo', 'thumbnail'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['View doctors'],
                ],
                [
                    'actions' => ['update', 'add-service', 'update-service', 'delete-service', 'schedule'],
                    'allow' => true,
                    'roles' => ['Update doctors'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'add-service' => ['POST'],
                'update-service' => ['POST'],
                'delete-service' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Doctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (count($dataProvider->models) == 1) {
            return $this->redirect(['view', 'id' => $dataProvider->models[0]->id]);
        } else {
            $this->pushNavHistory();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $this->pushNavHistory();

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
        return $this->navHistoryBack(['view', 'id' => $id, '#' => "branch-$branch"]);
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
        return $this->navHistoryBack(['view', 'id' => $model->doctor_id, '#' => "branch-$model->branch_id"]);
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
        return $this->navHistoryBack(['view', 'id' => $model->doctor_id, '#' => "branch-$model->branch_id"]);
    }

    /**
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $branches = Yii::$app->user->identity->activeClinic->branches;
    //     if (empty($branches)) {
    //         return $this->redirect(['/clinic/branches/create']);
    //     }

    //     $model = new Doctor();

    //     if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload()) {
    //         $model->languages = $model->languageArray;
    //         if ($model->save()) {
    //             // link the created doctor to the first branch by default
    //             $branches[0]->link('doctors', $model);
    //             Yii::$app->session->setFlash('success', Yii::t('clinic', 'New doctor record created successfully'));
    //             return $this->navHistoryBack(['schedule', 'doctor' => $model->id, 'branch' => $branches[0]->id]);
    //         } else {
    //             Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't save doctor data"));
    //             return $this->navHistoryBack(['index']);
    //         }
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

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
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't save doctor data"));
                return $this->navHistoryBack(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSchedule($id, $branch)
    {
        $doctor = $this->findModel($id);
        $model = DoctorClinicBranch::find()->where(['doctor_id' => $doctor, 'branch_id' => $branch])->with('branch')->one();

        if ($model === null || $model->branch->clinic_id != Yii::$app->user->identity->active_clinic) {
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

        if ($model->load(Yii::$app->request->post())) {
            $workingHoursPost = Yii::$app->request->post('WorkingHoursForm');
            $workingHoursError = null;
            
            // if (!empty($workingHoursPost)) {
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
            // }
            
            if ($model->validate() && $workingHoursError == null) {
                $model->workingHours = $workingHours;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('clinic', 'Doctor schedule updated'));
                    return $this->navHistoryBack(['view', 'id' => $id, '#' => "branch-$branch"]);
                }
            }

            Yii::$app->session->setFlash('error', $workingHoursError);
        }

        return $this->render('schedule', [
            'model' => $model,
            'doctor' => $doctor,
            'workingHours' => $workingHours,
        ]);
    }

    /**
     * Deletes an existing Doctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->navHistoryBack(['index']);
    // }

    /**
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Doctor::find()->alias('d')->joinWith('branches')->where(['d.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
