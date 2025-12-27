<?php

namespace clinic\controllers\patients;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use clinic\models\Branch;
use clinic\models\Patient;
use patient\models\AttachmentCategory;
use patient\models\Attachment;
use patient\models\AttachmentSearch;
use clinic\controllers\Controller;

/**
 * AttachmentsController implements the CRUD actions for Attachment model.
 */
class AttachmentsController extends Controller
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
                    'actions' => ['index', 'view', 'download'],
                    'allow' => true,
                    'roles' => ['View patient attachments'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['Create patient attachments'],
                ],
                // [
                //     'actions' => ['update'],
                //     'allow' => true,
                //     'roles' => ['Update patient attachments'],
                // ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all Attachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attachment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $filePath = $model->filePath;

        if (empty($filePath)) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->getHeaders()
            ->set('Content-Type', mime_content_type($filePath))
            ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
            ->set('Pragma', 'private')
            ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
            ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($filePath)));

        $response->content = file_get_contents($filePath);
        return $response->send();
    }

    /**
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $patient = Patient::find()->alias('p')->joinWith('clinicPatient')->where(['p.id' => $id])->one();
        $user = Yii::$app->user->identity;

        if ($patient === null) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $this->pushNavHistory(['/clinic/patients/view', 'id' => $patient->id, '#' => 'patient-attachments']);

        $branches = Branch::find()->where(['clinic_id' => $user->active_clinic])->select('name')->indexBy('id')->column();
        $categories = AttachmentCategory::find()->orderBy(['title' => SORT_ASC])->select('title')->indexBy('id')->column();

        $model = new Attachment([
            'patient_id' => $patient->id,
            'branch_id' => array_key_first($branches),
            // 'category_id' => array_key_first($categories),
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate(['branch_id', 'patient_id', 'category_id']) && $model->upload()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('patient', "Attachment uploaded successfully"));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('patient', "Couldn't upload attachment"));
            }
            return $this->navHistoryBack(['/clinic/patients/view', 'id' => $patient->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'patient' => $patient,
            'branches' => $branches,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Attachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing Attachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachment::find()->alias('a')->joinWith('branch')->where(['a.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
