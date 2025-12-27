<?php
namespace patient\controllers;

use Yii;
// use yii\base\InvalidArgumentException;
// use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use clinic\models\Clinic;
use patient\models\Patient;
use patient\models\Appointment;

/**
 * Root controller
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
                        // All other actions
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'sign-out' => ['post'],
            //     ],
            // ],
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
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionNewAppointment($clinic, $nationality, $cpr)
    {
        $cpr = trim($cpr);
        $nationality = trim($nationality);
        $clinic = (int) trim($clinic);

        $clinic = Clinic::findOne($clinic);

        if ($clinic === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        if (($patient = Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality])) === null) {
            $patient = new Patient(['cpr' => $cpr, 'nationality' => $nationality]);
        }

        return $this->render('new-appointment', [
            'clinic' => $clinic,
            'patient' => $patient,
        ]);
    }
}
