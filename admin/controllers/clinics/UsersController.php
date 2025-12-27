<?php

namespace admin\controllers\clinics;

use Yii;
use clinic\models\Clinic;
use clinic\models\User;
use clinic\models\UserSearch;
use clinic\models\ClinicLink;
use admin\models\ChangePasswordForm;
use clinic\models\rbac\Assignment;
use clinic\models\rbac\AssignmentItems;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UsersController extends Controller
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
                    'change-password' => ['POST'],
                    'update-blocked-state' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $clinics = Clinic::find()->select('name')->indexBy('id')->column();
        
        $passwordForm = new ChangePasswordForm();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'clinics' => $clinics,
            'passwordForm' => $passwordForm,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
            'relations' => $this->findClinics($id),
        ]);
    }

    public function actionUpdateBlockedState($user_id, $clinic_id)
    {
        $model = $this->findLink($user_id, $clinic_id);
        if ($model->isBlocked) {
            $model->unblock();
        } else {
            $model->block();
        }

        return $this->redirect(['view', 'id' => $user_id]);
    }

    public function actionUpdatePermissions($user_id, $clinic_id)
    {
        $link = $this->findLink($user_id, $clinic_id);
        $assignment = new Assignment($user_id, $link);
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
            return $this->redirect(['view', 'id' => $user_id]);
        }

        return $this->render('permissions', [
            'model' => $model,
            'items' => $items,
            'link' => $link,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRegister()
    {
        $model = new User();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            $model->link('clinics', Clinic::findOne($model->clinic_id), ['created_at' => time()]);
            $model->updateAttributes(['confirmed_at' => time()]);
            Yii::$app->session->addFlash('success', Yii::t('user', 'New user created successfully.'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionLinkClinic()
    {
        $model = new ClinicLink();

        if ($model->load(Yii::$app->request->post()) && $model->createLink()) {
            Yii::$app->session->addFlash('success', Yii::t('user', 'New link created successfully'));
            return $this->redirect(['view', 'id' => $model->user_id]);
        }

        return $this->render('link', [
            'model' => $model,
            'users' => User::find()->select('email')->indexBy('id')->column(),
            'clinics' => Clinic::find()->select('name')->indexBy('id')->column(),
        ]);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        $model->load(Yii::$app->request->post());
        // $password = Yii::$app->security->generateRandomString(8);
        // $model->new_password = $password;
        // $model->confirm_password = $password;

        if ($model->updatePassword(['userID', 'new_password', 'confirm_password'])) {
            Yii::$app->session->setFlash('success', Yii::t('user', "Password updated successfully"));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('user', "Couldn't update password"));
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(true, ['name', 'phone', 'email'])) {
            Yii::$app->session->addFlash('success', Yii::t('user', 'User updated successfully'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }

    protected function findLink($user_id, $clinic_id)
    {
        if (($model = ClinicLink::find()->joinWith(['user', 'clinic'])->where(['user_id' => $user_id, 'clinic_id' => $clinic_id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }

    /**
     * Finds the Clinics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id represents user id
     * @return Array $clinics the loaded user related clinics with their permissions
     */
    protected function findClinics($id)
    {
        $relations = ClinicLink::find()->joinWith('clinic')->where(['user_id' => $id])->all();
        $clinics = [];
        foreach ($relations as $item) {
            $clinics[$item->clinic_id] = new \stdClass();
            $clinics[$item->clinic_id]->link = $item;
            $clinics[$item->clinic_id]->clinic = $item->clinic;
            $clinics[$item->clinic_id]->assignment = new Assignment($id, $item);
        }

        return $clinics;
    }
}
