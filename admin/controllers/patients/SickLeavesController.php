<?php

namespace admin\controllers\patients;

use Yii;
use clinic\models\SickLeave;
use admin\models\SickLeaveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
     * Lists all SickLeave models.
     * @return mixed
     */
    public function actionIndex()
    {
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing SickLeave model.
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
     * Finds the SickLeave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SickLeave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SickLeave::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('patient', 'The requested page does not exist.'));
    }
}
