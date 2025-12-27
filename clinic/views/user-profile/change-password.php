<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('user', 'Change password');

$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Profile'), 'url' => ['/user-profile/index']];
$this->params['breadcrumbs'][] = $this->title;

$passwordTime = $model->user->password_updated_at == null ? '' : Yii::t('general', 'Last updated {time}', [
    'time' => Yii::$app->formatter->asRelativeTime($model->user->password_updated_at),
]);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <div class="card-body">
                    <h5 class="card-title"><?= $this->title ?></h5>
                    <div class="card-text text-secondary">
                        <b><?= Yii::t('user', 'Changing your password will sign you out.') ?></b>
                        <p><?= Yii::t('user', 'You will need to enter your new password to sign-in.') ?></p>
                    </div>
                    
                    <div class="mdc-list-item">
                        <div class="icon ml-0 salamat-light"><div class="material-icon">email</div></div>
                        <div class="text mx-0 salamat-color"><?= $model->user->email ?></div>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'inputOptions' => ['autocomplete' => 'off'],
                        ],
                    ]); ?>
                    

                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                    </div>

                    <p class="card-text"><small class="text-muted"><?= $passwordTime ?></small></p>

                    <div class="mdc-divider mb-3"></div>
                    <p class="card-text text-secondary"><?= Yii::t('user', 'Use at least 6 characters. Don’t use a password from another site, or something too obvious like your pet’s name.') ?></p>

                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7">
                            <?= $form->field($model, 'new_password')->passwordInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7">
                            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
                        </div>
                    </div>
                    
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton($this->title, [
                            'class' => "mdc-button btn-contained bg-salamat-color",
                        ]) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], [
                            'class' => "mdc-button btn-outlined salamat-color",
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
