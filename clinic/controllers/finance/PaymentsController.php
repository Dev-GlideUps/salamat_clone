<?php

namespace clinic\controllers\finance;

use Yii;
use clinic\models\Invoice;
use clinic\models\InvoicePayment;
use clinic\models\InvoicePaymentSearch;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;

/**
 * PaymentsController implements the CRUD actions for InvoicePayment model.
 */
class PaymentsController extends Controller
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
                    'roles' => ['View payments'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['Create payments'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Lists all InvoicePayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->pushNavHistory();

        $searchModel = new InvoicePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvoicePayment model.
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

    /**
     * Creates a new InvoicePayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new InvoicePayment(['invoice_id' => $id]);
        if ($model->branch->clinic_id != Yii::$app->user->identity->active_clinic) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $invoice = $model->invoice;

        if ($invoice->status == Invoice::STATUS_CANCELED) {
            Yii::$app->session->setFlash('error', Yii::t('finance', 'The selected invoice is cancelled'));
            return $this->navHistoryBack(['/finance/invoices/view', 'id' => $invoice->id]);
        }

        if ($invoice->balance == 0) {
            Yii::$app->session->setFlash('error', Yii::t('finance', 'Balance due is already zero'));
            return $this->navHistoryBack(['/finance/invoices/view', 'id' => $invoice->id]);
        }

        $model->amount_paid = $invoice->balance;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->amount_paid > $invoice->balance) {
                $model->addError('amount_paid', Yii::t('finance', 'Payment amount cannot be greater than balance due'));
                Yii::$app->session->setFlash('error', Yii::t('finance', 'Payment amount cannot be greater than balance due'));
            } elseif ($model->save()) {
                $invoice->paid += $model->amount_paid;
                $invoice->save(true, ['paid', 'updated_at']);
                Yii::$app->session->setFlash('success', Yii::t('finance', 'Payment saved successfully'));
                return $this->navHistoryBack(['/finance/invoices/view', 'id' => $invoice->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'invoice' => $invoice,
        ]);
    }

    /**
     * Finds the InvoicePayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvoicePayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvoicePayment::find()->alias('p')->joinWith('branch')->where(['p.id' => $id, 'b.clinic_id' => Yii::$app->user->identity->active_clinic])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
    }
}
