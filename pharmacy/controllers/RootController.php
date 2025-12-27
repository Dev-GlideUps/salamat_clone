<?php
namespace pharmacy\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use patient\models\Patient;
use clinic\models\Prescription;

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
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'rules' => [
            //         [
            //             'actions' => ['sign-in', 'error'],
            //             'allow' => true,
            //         ],
            //         [
            //             // All other actions
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
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

    public function actionPrescription($cpr, $nationality)
    {
        $cpr = trim($cpr);
        $nationality = trim($nationality);

        if (($patient = Patient::findOne(['cpr' => $cpr, 'nationality' => $nationality])) === null) {
            Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't find patient. please make sure CPR/SSN and Nationality are correct."));
            return $this->redirect(['index']);
        }

        if (($prescription = Prescription::find()->where(['patient_id' => $patient->id])->orderBy(['created_at' => SORT_DESC])->one()) === null) {
            Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't find any prescription for the patient."));
            return $this->redirect(['index']);
        }

        return $this->render('prescription', [
            'patient' => $patient,
            'model' => $prescription,
        ]);
    }
}
