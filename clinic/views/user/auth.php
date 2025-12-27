<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use common\widgets\ActiveForm;

$this->context->layout = 'plain';

$this->title = Yii::t('user', 'Salamat: Sign in');
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
            <a href="<?= \yii\helpers\Url::to(['sign-in']) ?>" class="selected-user">
                <div class="material-icon leading">person</div>
                <div class="text"><?= $model->email ?></div>
                <div class="material-icon trailing">keyboard_arrow_down</div>
            </a>
        </div>

        <?= $form->field($model, 'password')->passwordInput([
            'autofocus' => true,
        ]) ?>

        <a href="javascript: ;" class="mdt-subtitle-2 salamat-color"><?= Yii::t('user', 'Forgot password?') ?></a>

        <div class="text-right">
            <?= Html::submitButton(Yii::t('general', 'Next'), [
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
