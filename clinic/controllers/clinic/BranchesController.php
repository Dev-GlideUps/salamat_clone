<?php

namespace clinic\controllers\clinic;

use Yii;
use yii\helpers\ArrayHelper;
use clinic\models\Branch;
use clinic\models\BranchSearch;
use clinic\models\BranchService;
use clinic\models\BranchWorkingHours;
use common\models\WorkingHoursForm;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BranchController implements the CRUD actions for Branch model.
 */
class BranchesController extends Controller
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
                    'roles' => ['View branches'],
                ],
                [
                    'actions' => ['update', 'add-service', 'update-service', 'delete-service'],
                    'allow' => true,
                    'roles' => ['Update branches'],
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
     * Lists all Branch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchSearch();
        $searchModel->clinic_id = Yii::$app->user->identity->active_clinic;
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
     * Displays a single Branch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->pushNavHistory();

        $branch = $this->findModel($id);
        $serviceModel = new BranchService(['branch_id' => $id]);

        return $this->render('view', [
            'model' => $branch,
            'serviceModel' => $serviceModel,
        ]);
    }

    public function actionAddService($id)
    {
        $branch = $this->findModel($id);
        $model = new BranchService(['branch_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service added"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot add branch service"));
        }
        return $this->navHistoryBack(['view', 'id' => $id, '#' => 'branch-services']);
    }

    public function actionUpdateService()
    {
        $id = Yii::$app->request->post('BranchService')['id'];
        $model = BranchService::findOne($id);
        $branch = $this->findModel($model->branch_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service updated"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot update branch service"));
        }
        return $this->navHistoryBack(['view', 'id' => $model->branch_id, '#' => 'branch-services']);
    }

    public function actionDeleteService()
    {
        $id = Yii::$app->request->post('id');
        $model = BranchService::findOne($id);
        $branch = $this->findModel($model->branch_id);

        if ($model->delete() !== false) {
            Yii::$app->session->setFlash('success', Yii::t('clinic', "Branch service deleted"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Cannot delete branch service"));
        }
        return $this->navHistoryBack(['view', 'id' => $model->branch_id, '#' => 'branch-services']);
    }

    /**
     * Creates a new Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
        // $model = new Branch();
        // $model->clinic_id = Yii::$app->user->identity->active_clinic;
        
        // if ($model->load(Yii::$app->request->post())) {
        //     $model->coordinates = Yii::$app->request->post('Branch')['coordinatesInput'];
        //     if ($model->save()) {
        //         return $this->navHistoryBack(['view', 'id' => $model->id]);
        //     } else {
        //     }
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
    // }

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
        // $model->coordinatesInput = $model->coordinates;
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
            
            // $model->coordinates = Yii::$app->request->post('Branch')['coordinatesInput'];
            if ($model->save() && $workingHoursError == null) {
                $branchWorkingHours->load(Yii::$app->request->post());
                $branchWorkingHours->workingHours = $workingHours;
                if ($branchWorkingHours->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('clinic', 'Branch updated'));
                    return $this->navHistoryBack(['view', 'id' => $model->id]);
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
    // public function actionDelete($id)
    // {
        // $this->findModel($id)->delete();
        // return $this->navHistoryBack(['index']);
    // }

    /**
     * Finds the Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branch::findOne([
            'id' => $id,
            'clinic_id' => Yii::$app->user->identity->active_clinic,
        ])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
