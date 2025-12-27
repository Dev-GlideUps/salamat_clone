<?php

namespace clinic\controllers\patients;

use Yii;
use clinic\models\Diagnosis;
use clinic\models\Medicine;
use clinic\models\Prescription;
use clinic\models\PrescriptionItem;
use clinic\models\PrescriptionSearch;
use clinic\models\DoctorClinicBranch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrescriptionsController implements the CRUD actions for Prescription model.
 */
class PrescriptionsController extends Controller
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
                    'roles' => ['View prescriptions'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Lists all Prescription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new PrescriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prescription model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->pushNavHistory();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        $content = $this->renderPartial('pdf', [
            'model' => $model,
        ]);

        $PDF = new \Mpdf\Mpdf([
            'mode' => '',
            'format' => 'A4',
            'orientation' => 'P',
            'destination' => 'I',
            'dpi' => 300,
            'tempDir' => Yii::getAlias('@clinic/documents/temp'),
        ]);
        
        $stylesheet = file_get_contents(Yii::getAlias('@clinic/web/css/pdf.css'));
        
        $PDF->SetTitle("PRESCRIPTION #{$model->id}");
        $PDF->SetHTMLFooter(\yii\helpers\Html::tag('div', \yii\helpers\Html::img(Yii::getAlias('@web/img/logo2.png')), [
            'class' => 'salamat-footer-logo',
        ]));
        $PDF->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $PDF->WriteHTML($content,\Mpdf\HTMLParserMode::HTML_BODY);

        return $PDF->Output("PRESCRIPTION #{$model->id}.pdf", \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Creates a new Prescription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $diagnosis = Diagnosis::find()->alias('dg')->joinWith(['branch b'])->where(['dg.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one();
        $user = Yii::$app->user->identity;

        if ($diagnosis === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        // should be able to create new prescription for old diagnosis like chronic diseases
        // if (time() > strtotime('+1 day', $diagnosis->created_at)) {
        //     Yii::$app->session->setFlash('error', Yii::t('patient', 'Cannot create prescription after 24 hours'));
        //     return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        // }

        if (!$user->isDoctor) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Only a doctor can perform this action"));
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
        }

        $medicines = Medicine::find()->where(['clinic_id' => $user->active_clinic])->orderBy(['name' => SORT_ASC])->all();
        if (empty($medicines)) {
            Yii::$app->session->setFlash('error', Yii::t('clinic', "Medicine list is empty"));
            return $this->redirect(['/clinic/medicines']);
        }

        $branches = DoctorClinicBranch::find()->joinWith('branch')->where(['doctor_id' => $user->doctor->id, 'b.clinic_id' => $user->active_clinic])->select('b.name')->indexBy('b.id')->column();

        $model = new Prescription([
            'patient_id' => $diagnosis->patient_id,
            'diagnosis_id' => $diagnosis->id,
            'branch_id' => array_key_first($branches),
        ]);

        $items = [new PrescriptionItem()];
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if (!isset($branches[$model->branch_id])) {
                Yii::$app->session->setFlash('error', Yii::t('clinic', 'Invalid branch'));
            } elseif($model->save()) {
                $items = [];
                $itemError = false;
                foreach (Yii::$app->request->post('PrescriptionItem') as $i => $item) {
                    $items[$i] = new PrescriptionItem($item);
                    $items[$i]->prescription_id = $model->id;
                    $itemError = !$items[$i]->validate() || $itemError;
                    if (!$itemError) {
                        $items[$i]->save();
                    }
                }
                
                if (!$itemError) {
                    Yii::$app->session->setFlash('success', Yii::t('patient', "Prescription created successfully"));
                    return $this->navHistoryBack(['/clinic/patients/view', 'id' => $diagnosis->patient_id]);
                }
                Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't create prescription"));
                $model->delete();

                $model = new Prescription([
                    'patient_id' => $diagnosis->patient_id,
                    'diagnosis_id' => $diagnosis->id,
                    'branch_id' => array_key_first($branches),
                ]);
            }
        }

        return $this->render('create', [
            'branches' => $branches,
            'diagnosis' => $diagnosis,
            'medicines' => $medicines,
            'medModel' => new Medicine(),
            'model' => $model,
            'items' => $items,
        ]);
    }

    /**
     * Finds the Prescription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prescription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prescription::find()->alias('p')->joinWith(['branch b'])->where(['p.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
