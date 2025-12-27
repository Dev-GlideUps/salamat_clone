<?php

namespace clinic\controllers\clinic;

use Yii;
use clinic\models\Medicine;
use clinic\models\MedicineSearch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MedicinesController implements the CRUD actions for Medicine model.
 */
class MedicinesController extends Controller
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
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['View medicines'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['Create medicines'],
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['Update medicines'],
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['Delete medicines'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create' => ['POST'],
                'update' => ['POST'],
                'delete' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Medicine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new MedicineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => new Medicine(),
        ]);
    }

    /**
     * Displays a single Medicine model.
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
     * Creates a new Medicine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Medicine(['clinic_id' => Yii::$app->user->identity->active_clinic]);
        $model->load(Yii::$app->request->post());
        $request = Yii::$app->request;

        if ($model->save()) {
            if ($request->isAjax) {
                return \yii\helpers\Json::encode([
                    'message' => Yii::t('clinic', 'New medicine added successfully'),
                    'button' => Yii::t('general', 'Done'),
                    'success' => true,
                    'item' => \yii\helpers\Html::tag('option', $model->name, ['value' => $model->name, 'data-forms' => $model->forms]),
                ]);
            }

            Yii::$app->session->setFlash('success', Yii::t('clinic', 'New medicine added successfully'));
            return $this->navHistoryBack(['view', 'id' => $model->id]);
        }

        if ($request->isAjax) {
            return \yii\helpers\Json::encode([
                'message' => $model->getErrorSummary(true)[0],
                'button' => Yii::t('general', 'Try again'),
                'success' => false,
                'item' => '',
            ]);
        }

        Yii::$app->session->setFlash('error', $model->getErrorSummary(true)[0]);
        return $this->navHistoryBack(['index']);
    }

    /**
     * Updates an existing Medicine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('Medicine')['id'];
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());

        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', 'Medicine record updated successfully'));
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true)[0]);
        }

        return $this->navHistoryBack(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Medicine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('clinic', 'Medicine deleted successfully'));
        return $this->navHistoryBack(['index']);
    }

    /**
     * Finds the Medicine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Medicine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Medicine::findOne(['id' => $id, 'clinic_id' => Yii::$app->user->identity->active_clinic])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('clinic', 'The requested page does not exist.'));
    }
}
