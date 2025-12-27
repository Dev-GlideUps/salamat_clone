<?php
namespace admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use admin\models\SignInForm;
use yii\data\ActiveDataProvider;

use clinic\models\clinic\AppointmentSms;

/**
 * Site controller
 */
class RootController extends Controller
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
                        'actions' => ['sign-in', 'error','sms-log'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Sign-in action.
     *
     * @return string
     */
    public function actionSignIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignInForm();
        if ($model->load(Yii::$app->request->post()) && $model->signIn()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('sign-in', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Sign-out action.
     *
     * @return string
     */
    public function actionSignOut()
    {
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionSmsLog()
    {
        // $count = AppointmentSms::find();
                // echo "<pre>";print_r($_GET['AppointmentSms']['created_at']);die;

        if(isset($_GET['AppointmentSms']) && !empty($_GET['AppointmentSms']['created_at'])>0){
            $date = $_GET['AppointmentSms']['created_at'];
            $to =strtotime(date("Y-m-1", strtotime($date)));
            $from=strtotime(date("Y-m-t", strtotime($date)));
            $count = AppointmentSms::find()->select(['clinic_id', 'COUNT(*) count'])->where(['between', 'created_at', $to, $from ])->groupBy(['clinic_id']);

        }else{
            $count = AppointmentSms::find()->select(['clinic_id', 'COUNT(*) count'])->groupBy(['clinic_id']);

        }
        $appointSms = New AppointmentSms;
        // echo "<pre>";print_r($count);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $count,
        ]);
        return $this->render('sms-log', [
            'dataProvider' => $dataProvider,
            'model'=>$appointSms
        ]);
    }
    public function actionServerInfo()
    {
        return $this->render('server-info');
    }
}
