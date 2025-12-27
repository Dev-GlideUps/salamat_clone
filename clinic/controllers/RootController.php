<?php
namespace clinic\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use clinic\controllers\Controller;
use yii\db\Query;
use clinic\models\Branch;
use clinic\models\ClinicPatient;
use clinic\models\Appointment;
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
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => $this->_accessControl,
            'rules' => [
                [
                    'actions' => ['error'],
                    'allow' => true,
                ],
                [
                    // All other actions
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $clinicId = Yii::$app->user->identity->active_clinic;
        $thisMonth = strtotime(date('Y-m-01'));

        $patients = (new Query())
            ->select(["COUNT(*) AS total", "SUM(IF(created_at >= $thisMonth, 1, 0)) as this_month"])
            ->from(ClinicPatient::tableName())
            ->where(['clinic_id' => $clinicId])
            ->one();
        $appointments = (new Query())->select(["COUNT(*) AS total", "SUM(IF(app.created_at >= $thisMonth, 1, 0)) as this_month"])
            ->from(Appointment::tableName() . " app")
            ->leftJoin(Branch::tableName() . " b", "app.branch_id = b.id")
            ->where(['b.clinic_id' => $clinicId])
            ->one();
        $prescriptions = (new Query())->select(["COUNT(*) AS total", "SUM(IF(pr.created_at >= $thisMonth, 1, 0)) as this_month"])
            ->from(Prescription::tableName() . " pr")
            ->leftJoin(Branch::tableName() . " b", "pr.branch_id = b.id")
            ->where(['b.clinic_id' => $clinicId])
            ->one();

        return $this->render('index', [
            'totalPatients' => $patients['total'],
            'thisMonthPatients' => $patients['this_month'] ?? 0,
            'totalAppointments' => $appointments['total'],
            'thisMonthAppointments' => $appointments['this_month'] ?? 0,
            'totalPrescriptions' => $prescriptions['total'],
            'thisMonthPrescriptions' => $prescriptions['this_month'] ?? 0,
        ]);
    }

    // public function actionClinicLogo()
    // {
    //     $file = Yii::$app->user->identity->activeClinic->logo;
    //     $imgFullPath = \Yii::getAlias("@clinic/documents/clinics/logo/$file");
    //     if (empty($file) || !file_exists($imgFullPath)) {
    //         throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    //     }

    //     $response = Yii::$app->getResponse();
    //     $response->format = \yii\web\Response::FORMAT_RAW;
    //     $response->getHeaders()
    //         ->set('Content-Type', mime_content_type($imgFullPath))
    //         ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
    //         ->set('Pragma', 'private')
    //         ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
    //         ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($imgFullPath)));

    //     $response->content = file_get_contents($imgFullPath);
    //     return $response->send();
    // }

    public function actionGoBack($currentRoute)
    {
        return $this->navHistoryBack(['index'], $currentRoute);
    }
}
