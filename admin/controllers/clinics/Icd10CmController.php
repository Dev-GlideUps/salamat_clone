<?php

namespace admin\controllers\clinics;

use Yii;
use admin\models\DiagnosisCode;
use admin\models\DiagnosisCodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Icd10CmController implements the CRUD actions for DiagnosisCode model.
 */
class Icd10CmController extends Controller
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
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DiagnosisCode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DiagnosisCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new DiagnosisCode();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single DiagnosisCode model.
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
     * Creates a new DiagnosisCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DiagnosisCode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('patient', "ICD-10 record created"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't create ICD-10 record"));
        }
        
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Updates an existing DiagnosisCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('DiagnosisCode')['id'];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('patient', "ICD-10 record updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't update ICD-10 record"));
        }
        
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing DiagnosisCode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('patient', "ICD-10 record deleted"));
        return $this->redirect(['index']);
    }

    /**
     * Finds the DiagnosisCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DiagnosisCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DiagnosisCode::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('patient', 'The requested page does not exist.'));
    }
}
