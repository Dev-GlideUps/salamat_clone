<?php

namespace clinic\controllers\patients\diagnoses;

use clinic\models\FavoriteDiagnosis;
use clinic\models\FavoriteDiagnosisSearch;
use Yii;
use clinic\models\Patient;
use clinic\models\Diagnosis;
use clinic\models\DiagnosisSearch;
use admin\models\DiagnosisCode;
use clinic\models\DoctorClinicBranch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoriteListController implements the CRUD actions for Diagnosis model.
 */
class FavoriteListController extends Controller
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
                'delete' => ['POST'],
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

        $searchModel = new FavoriteDiagnosisSearch();
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
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        if (!$user->isDoctor) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', 'Only a doctor can perform this action'));
            return $this->navHistoryBack(['index']);
        }

        $model = new FavoriteDiagnosis([
            'clinic_id' => $user->active_clinic,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', 'Diagnosis added successfully'));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
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
        $user = Yii::$app->user->identity;

        if (!$user->isDoctor) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', 'Only a doctor can perform this action'));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($user->id != $model->created_by) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', 'You cannot update this diagnosis'));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('clinic', 'Favorite diagnosis updated successfully'));
                return $this->navHistoryBack(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing favorite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('patient', 'Favorite diagnosis deleted successfully'));
        return $this->navHistoryBack(['index']);
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
        if (($model = FavoriteDiagnosis::find()->alias('dg')->joinWith('clinic c')->where(['dg.id' => $id, 'c.id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
