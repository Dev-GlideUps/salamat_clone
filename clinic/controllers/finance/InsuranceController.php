<?php

namespace clinic\controllers\finance;

use Yii;
use clinic\controllers\Controller;
use yii\web\NotFoundHttpException;

use clinic\models\InvoiceSearch;

/**
 * InsuranceController
 */
class InsuranceController extends Controller
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
                    'actions' => ['claims'],
                    'allow' => true,
                    'roles' => ['View invoices'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Lists all Invoice insurance claims.
     * @return mixed
     */
    public function actionClaims()
    {
        $this->pushNavHistory();

        $searchModel = new InvoiceSearch();
        $params = Yii::$app->request->queryParams;
        $params['InvoiceSearch']['has_insurance'] = true;
        $dataProvider = $searchModel->search($params);

        return $this->render('claims', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
