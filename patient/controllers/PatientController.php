<?php

namespace patient\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use patient\models\Patient;

class PatientController extends Controller
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

    public function actionPhoto($file)
    {
        $imgFullPath = \Yii::getAlias("@patient/documents/patients/photo/$file");
        if (!file_exists($imgFullPath)) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->getHeaders()
            ->set('Content-Type', mime_content_type($imgFullPath))
            ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
            ->set('Pragma', 'private')
            ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
            ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($imgFullPath)));

        $response->content = file_get_contents($imgFullPath);
        return $response->send();
    }

    public function actionThumbnail($file)
    {
        $imgFullPath = \Yii::getAlias("@patient/documents/patients/photo/thumbs/$file");
        if (!file_exists($imgFullPath)) {
            throw new NotFoundHttpException(Yii::t('general', 'The requested page does not exist.'));
        }

        $response = Yii::$app->getResponse();
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->getHeaders()
            ->set('Content-Type', mime_content_type($imgFullPath))
            ->set('Cache-Control', 'private, max-age='.(60 * 60 * 24 * 30).', pre-check='.(60 * 60 * 24 * 30))
            ->set('Pragma', 'private')
            ->set('Expires', gmdate('D, d M Y H:i:s T', strtotime('+30 days')))
            ->set('Last-Modified', gmdate('D, d M Y H:i:s T', filemtime($imgFullPath)));

        $response->content = file_get_contents($imgFullPath);
        return $response->send();
    }
}