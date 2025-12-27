<?php
namespace clinic\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use clinic\controllers\Controller;
use yii\filters\VerbFilter;
use clinic\models\SignInForm;
use clinic\models\ClinicSelectForm;

/**
 * Root controller
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => $this->_accessControl,
                'rules' => [
                    [
                        'actions' => ['sign-in', 'auth'],
                        'allow' => true,
                    ],
                    [
                        // All other actions
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'sign-out' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionSignIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignInForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->set('sign_in_auth_email', $model->email);
            return $this->redirect(['auth']);
        }
        return $this->render('sign-in', [
            'model' => $model,
        ]);
    }
    
    public function actionAuth()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $email = Yii::$app->session->get('sign_in_auth_email');
        if (empty($email)) {
            return $this->redirect(['sign-in']);
        }

        $model = new SignInForm(['email' => $email]);
        $model->scenario = SignInForm::SCENARIO_AUTH;
        if ($model->load(Yii::$app->request->post()) && $model->signIn()) {
            Yii::$app->session->remove('sign_in_auth_email');

            return $this->redirect(['select-clinic', 'auto' => true]);
        }
        return $this->render('auth', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionSignOut()
    {
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    public function actionSelectClinic($auto = false)
    {
        $user = Yii::$app->user->identity;

        if ($auto && !empty($user->active_clinic)) {
            goto redirect;
        }

        $clinics = $user->clinics;
        if (count($clinics) == 1) {
            $user->updateAttributes(['active_clinic' => $clinics[0]->id]);
            goto redirect;
        }

        $model = new ClinicSelectForm();
        $model->user_id = $user->id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->updateAttributes(['active_clinic' => $model->clinic_id]);
            goto redirect;
        }

        return $this->render('select-clinic', [
            'model' => $model,
            'clinics' => $clinics,
        ]);

        redirect:
        if (empty($user->password_updated_at)) {
            return $this->redirect(['/user-profile/first-sign-in']);
        }
        return $this->goHome();
    }
}
