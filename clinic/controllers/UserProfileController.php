<?php
namespace clinic\controllers;

use Yii;
use clinic\controllers\Controller;
use clinic\models\ChangePasswordForm;
use clinic\models\UserProfileForm;

/**
 * User Settings controller
 */
class UserProfileController extends Controller
{
    public function actionIndex()
    {
        $this->pushNavHistory();

        return $this->render('index');
    }

    public function actionUpdate()
    {
        $model = new UserProfileForm();

        $model->name = $model->user->name;
        $model->phone = $model->user->phone;
        $model->dark_theme = $model->user->dark_theme;

        if ($model->load(Yii::$app->request->post()) && $model->updateProfile()) {
            Yii::$app->session->setFlash('info', Yii::t('user', "Profile updated successfully"));

            return $this->navHistoryBack(['/user-profile/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->updatePassword()) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('info', Yii::t('user', "Password changed successfully"));

            return $this->goHome();
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    public function actionFirstSignIn()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->updatePassword(['new_password', 'confirm_password'])) {
            Yii::$app->session->setFlash('info', Yii::t('user', "Password updated successfully"));
            return $this->redirect(['/user/select-clinic', 'auto' => true]);
        }

        return $this->render('first-sign-in', [
            'model' => $model,
        ]);
    }

    public function actionChangeTheme()
    {
        if (!Yii::$app->request->isAjax) {
            $this->redirect(['index']);
        }

        $user = Yii::$app->user->identity;

        try {
            $darkTheme = (int) Yii::$app->request->post('darkTheme');
            $user->updateAttributes(['dark_theme' => $darkTheme]);

            return $user->dark_theme;
        } catch (\Exception $e) {
            return $user->dark_theme;
        }
    }
}
