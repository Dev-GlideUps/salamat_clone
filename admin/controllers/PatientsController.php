<?php

namespace admin\controllers;

use Yii;
use admin\models\Patient;
use admin\models\PatientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
     * Lists all Patient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PatientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Patient model.
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
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Patient();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->upload() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Patient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('patient', "Patient record deleted"));
        return $this->redirect(['index']);
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
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('patient', 'The requested page does not exist.'));
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
}
