<?php

namespace clinic\controllers\clinic;

use Yii;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use clinic\models\User;
use clinic\models\UserSearch;
use clinic\models\ClinicLink;
use clinic\models\rbac\Assignment;
use clinic\models\rbac\AssignmentItems;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
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
                    'roles' => ['View users'],
                ],
                [
                    'actions' => ['update-blocked-state', 'update-permissions'],
                    'allow' => true,
                    'roles' => ['Control users'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'update-blocked-state' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new UserSearch();
        $searchModel->clinic_id = Yii::$app->user->identity->active_clinic;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->pushNavHistory();

        $link = $this->findLink($id);
        return $this->render('view', [
            'model' => $link->user,
            'link' => $this->findLink($id),
            'assignment' => $this->findAssignment($id),
        ]);
    }

    public function actionUpdateBlockedState($id)
    {
        if ($id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('user', 'Action cannot be performed on the current session user'));
            return $this->navHistoryBack(['view', 'id' => $id]);
        }

        $model = $this->findLink($id);
        if ($model->isBlocked) {
            $model->unblock();
        } else {
            $model->block();
        }

        return $this->navHistoryBack(['view', 'id' => $id]);
    }

    public function actionUpdatePermissions($id)
    {
        $user = $this->findModel($id);
        $assignment = $this->findAssignment($id);
        $model = new AssignmentItems();

        $items = $assignment->getItems(true);
        $assigned = $assignment->getAssignments();
        $itemsList = array_merge($items['roles'], $items['permissions']);

        $model->items = [];
        foreach ($itemsList as $item => $info) {
            $model->items[$item] = in_array($item, $assigned);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $assignItems = [];
            $revokeItems = [];
            foreach ($model->items as $item => $assign) {
                // check if item is in the list of available items
                if (!isset($itemsList[$item])) {
                    continue;
                }

                // if item should be assigned
                if ($assign == true && !in_array($item, $assigned)) {
                    $assignItems[] = $item;
                }

                // if item should be revoked
                if ($assign == false && in_array($item, $assigned)) {
                    $revokeItems[] = $item;
                }
            }

            $assignCount = $assignment->assign($assignItems);
            $revokeCount = $assignment->revoke($revokeItems);

            Yii::$app->session->setFlash('success', Yii::t('user', 'User permissions updated'));
            return $this->navHistoryBack(['view', 'id' => $user->id]);
        }

        return $this->render('permissions', [
            'model' => $model,
            'items' => $items,
            'user' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::find()->joinWith(["clinicLinks x"], true, 'INNER JOIN')->where([
            'id' => $id,
            'x.clinic_id' => Yii::$app->user->identity->active_clinic,
        ])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('clinic', 'The requested page does not exist.'));
    }

    protected function findLink($id)
    {
        if (($model = ClinicLink::find()->joinWith('user')->where(['user_id' => $id, 'clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('clinic', 'The requested page does not exist.'));
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAssignment($id)
    {
        $clinicId = Yii::$app->user->identity->active_clinic;
        $relation = ClinicLink::findOne(['user_id' => $id, 'clinic_id' => $clinicId]);

        if ($relation !== null) {
            return new Assignment($id, $relation);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
