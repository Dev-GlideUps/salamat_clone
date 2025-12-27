<?php
namespace clinic\controllers;

// use yii\filters\AccessControl;
use clinic\filters\ActiveClinicAccessControl;
use yii\web\Controller as BaseController;

class Controller extends BaseController
{
    protected $_accessControl = 'yii\filters\AccessControl';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => $this->_accessControl,
                'rules' => [
                    [
                        // All actions
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'activeClinic' => [
                'class' => ActiveClinicAccessControl::className(),
            ],
        ];
    }

    protected function getNavHistory()
    {
        $session = \Yii::$app->session;
        $session->open();
        $history = $session['nav_history'];
        if (!is_array($history)) {
            $history = [];
        }
        return $history;
    }

    protected function pushNavHistory($route = null)
    {
        $history = $this->getNavHistory();
        
        if ($route === null) {
            $route = array_merge(["/".\Yii::$app->controller->route], \Yii::$app->request->queryParams);
        }

        if (!empty($history) && end($history)[0] == $route[0]) {
            array_pop($history);
        }

        $history[] = $route;
        \Yii::$app->session->set('nav_history', $history);
    }

    protected function popNavHistory($currentRoute = false)
    {
        $history = $this->getNavHistory();

        if (!empty($history) && end($history)[0] == $currentRoute) {
            array_pop($history);
            \Yii::$app->session->set('nav_history', $history);
        }
        return end($history);
    }

    protected function navHistoryBack($url, $currentRoute = false)
    {
        $historyPage = $this->popNavHistory($currentRoute);
        if (!empty($historyPage)) {
            return $this->redirect($historyPage);
        }
        return $this->redirect($url);
    }
}