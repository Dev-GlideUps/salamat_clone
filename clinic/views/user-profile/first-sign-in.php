<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use common\widgets\ActiveForm;

$this->context->layout = 'plain';

$this->title = Yii::t('user', 'Salamat: Update password');
?>

<div class="auth-container">
    <div class="pexels">Photo by <a href="https://www.pexels.com/@suzyhazelwood" target="_blank">Suzy Hazelwood</a></div>

    <?php $form = ActiveForm::begin([
        'id' => 'sign-in-form',
        'options' => ['class' => 'auth-form'],
    ]); ?>
        <div class="header">
            <div class="logo"><?php include Yii::getAlias('@common/web/img/logo.svg') ?></div>
            <h5><?= Yii::t('user', 'Welcome') ?></h5>
            <p class="text-secondary"><?= Yii::t('user', 'please update your password') ?></p>
            <div class="mdc-list-item">
                <div class="material-icon leading">person</div>
                <div class="text"><?= Yii::$app->user->identity->email ?></div>
            </div>
        </div>

        <?= $form->field($model, 'new_password')->passwordInput([
            'autofocus' => true,
        ]) ?>
        <?= $form->field($model, 'confirm_password')->passwordInput([
            'autofocus' => true,
        ]) ?>

        <div class="text-right">
            <?= Html::submitButton(Yii::t('general', 'Update'), [
                'class' => 'mdc-button btn-contained bg-salamat-color',
            ]) ?>
        </div>

        <div class="loading-block">
            <div class="mdc-progress-track salamat-color">
                <div class="indicator indeterminate"></div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
