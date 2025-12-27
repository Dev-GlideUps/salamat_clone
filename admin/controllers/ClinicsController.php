<?php

namespace admin\controllers;

use Yii;
use admin\models\Clinic;
use clinic\models\ClinicSearch;
use clinic\models\Branch;
use clinic\models\BranchWorkingHours;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ClinicController implements the CRUD actions for Clinic model.
 */
class ClinicsController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all Clinic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClinicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clinic model.
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

    public function actionLogo($file)
    {
        $imgFullPath = \Yii::getAlias("@clinic/documents/clinics/logo/$file");
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
     * Creates a new Clinic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clinic();
        $branch = new Branch();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload()) {
            $model->packages = $model->packageArray;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', "Clinic created successfully"));
                $branch->load(Yii::$app->request->post());
                $branch->coordinates = Yii::$app->request->post('Branch')['coordinatesInput'];
                $branch->clinic_id = $model->id;
                if ($branch->save()) {
                    $workingHours = new BranchWorkingHours([
                        'branch_id' => $branch->id,
                    ]);
                    $workingHours->save();
                    Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch created successfully"));
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create branch"));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't create clinic"));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'branch' => $branch,
        ]);
    }

    /**
     * Updates an existing Clinic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->packageArray = $model->packages;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload()) {
            $model->packages = $model->packageArray;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', 'Clinic record updated successfully'));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't save clinic data"));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Clinic model.
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
     * Finds the Clinic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clinic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clinic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('clinic', 'The requested page does not exist.'));
    }
}
