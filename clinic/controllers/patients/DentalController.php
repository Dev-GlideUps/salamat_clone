<?php

namespace clinic\controllers\patients;

use Yii;
use clinic\models\Branch;
use clinic\models\Patient;
use clinic\models\dental\Record;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;

/**
 * DentalController implements the CRUD actions for Diagnosis model.
 */
class DentalController extends Controller
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
                    // All actions
                    'allow' => true,
                    'roles' => ['View diagnoses'],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all Diagnosis models.
     * @return mixed
     */
    public function actionIndex($id, $tooth = null)
    {
        $this->pushNavHistory();

        $patient = Patient::find()->alias('p')->joinWith('clinicPatient')->where(['p.id' => $id])->one();
        $user = Yii::$app->user->identity;

        if ($patient === null || !$user->activeClinic->has('dental')) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $branches = $user->activeClinic->getBranches()->select('name')->indexBy('id')->column();

        $records = Record::find()->alias('rec')->where(['rec.patient_id' => $patient->id])->joinWith(['branch b', 'category cat', 'procedure proc', 'doctor doc'])->orderBy(['rec.procedure_date' => SORT_DESC, 'rec.created_at' => SORT_DESC])->all();

        $newRecord = new Record([
            'branch_id' => array_key_first($branches),
            'patient_id' => $patient->id,
            'teeth' => $tooth,
            'procedure_date' => date('Y-m-d'),
        ]);

        if ($newRecord->load(Yii::$app->request->post())) {
            if ($newRecord->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "New procedure added"));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Couldn't add new procedure"));
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'patient' => $patient,
            'records' => $records,
            'tooth' => $tooth,
            'branches' => $branches,
            'newRecord' => $newRecord,
        ]);
    }
}
