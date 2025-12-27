<?php

namespace admin\controllers\clinics;

use Yii;
use yii\helpers\ArrayHelper;
use clinic\models\Clinic;
use clinic\models\Branch;
use clinic\models\BranchSearch;
use clinic\models\BranchService;
use clinic\models\BranchWorkingHours;
use common\models\WorkingHoursForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BranchesController implements the CRUD actions for Branch model.
 */
class BranchesController extends Controller
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
                    'add-service' => ['POST'],
                    'update-service' => ['POST'],
                    'delete-service' => ['POST'],
                    'branch-block' => ['POST'],
                    'un-block' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Branch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $clinics = Clinic::find()->select('name')->indexBy('id')->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'clinics' => $clinics,
        ]);
    }

    /**
     * Displays a single Branch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $branch = $this->findModel($id);
        $serviceModel = new BranchService(['branch_id' => $id]);

        return $this->render('view', [
            'model' => $branch,
            'serviceModel' => $serviceModel,
        ]);
    }

    public function actionAddService($id)
    {
        $model = new BranchService(['branch_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service added"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot add branch service"));
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'branch-services']);
    }

    public function actionUpdateService()
    {
        $id = Yii::$app->request->post('BranchService')['id'];
        $model = BranchService::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot update branch service"));
        }
        return $this->redirect(['view', 'id' => $model->branch_id, '#' => 'branch-services']);
    }

    public function actionDeleteService()
    {
        $id = Yii::$app->request->post('id');
        $model = BranchService::findOne($id);

        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service deleted"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot delete branch service"));
        }
        return $this->redirect(['view', 'id' => $model->branch_id, '#' => 'branch-services']);
    }

    /**
     * Creates a new Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Branch();
        $workingHours = [
            7 => null,
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
        ];
        
        $branchWorkingHours = new BranchWorkingHours();

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
            
            $model->coordinates = Yii::$app->request->post('Branch')['coordinatesInput'];
            if ($model->save() && $workingHoursError == null) {
                $branchWorkingHours->load(Yii::$app->request->post());
                $branchWorkingHours->branch_id = $model->id;
                $branchWorkingHours->workingHours = $workingHours;
                if ($branchWorkingHours->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('clinic', 'Branch created'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            Yii::$app->session->setFlash('error', $workingHoursError);
        }

        return $this->render('create', [
            'model' => $model,
            'branchWorkingHours' => $branchWorkingHours,
            'workingHours' => $workingHours,
        ]);
    }

    /**
     * Updates an existing Branch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->coordinatesInput = $model->coordinates;
        $workingHours = [
            7 => null,
            1 => null,
            2 => null,
            3 => null,
            4 => null,
            5 => null,
            6 => null,
        ];

        $branchWorkingHours = $model->workingHoursModel;
        if ($branchWorkingHours == null) {
            $branchWorkingHours = new BranchWorkingHours(['branch_id' => $model->id]);
        } else {
            $workingHours = $branchWorkingHours->workingHours;
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
            
            $model->coordinates = Yii::$app->request->post('Branch')['coordinatesInput'];
            if ($model->save() && $workingHoursError == null) {
                $branchWorkingHours->load(Yii::$app->request->post());
                $branchWorkingHours->workingHours = $workingHours;
                if ($branchWorkingHours->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('clinic', 'Branch updated'));
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            Yii::$app->session->setFlash('error', $workingHoursError);
        }

        return $this->render('update', [
            'model' => $model,
            'branchWorkingHours' => $branchWorkingHours,
            'workingHours' => $workingHours,
        ]);
    }

    /**
     * Deletes an existing Branch model.
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
     * Finds the Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

     public function actionBranchBlock($id){
        $model = $this->findModel($id);

        if($model){
            $model->block = 2;
            
            if ($model->save()) {
                // print_r($model);die;

                Yii::$app->session->setFlash('success', Yii::t('clinic', 'Branch Blocked successfully'));
               
            } else {
                Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't Blocked Branch data"));
            }
        }
        
        return $this->redirect(['index']);
    }
    public function actionUnBlock($id){
        $model = $this->findModel($id);
        $model->block = 1;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', 'Branch Unblocked successfully'));
           
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Couldn't Unlocked Branch data"));
        }
        return $this->redirect(['index']);


    }

    protected function findModel($id)
    {
        if (($model = Branch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('clinic', 'The requested page does not exist.'));
    }
}
