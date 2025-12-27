<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('user', 'Profile & personalization');

$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Profile'), 'url' => ['/user-profile/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card">

                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'inputOptions' => ['autocomplete' => 'off'],
                    ],
                ]); ?>
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('user', 'Profile') ?></h5>
                    <p class="card-text text-secondary"><?= Yii::t('user', 'Update your profile information.') ?></p>
                    
                    <div class="mdc-list-item">
                        <div class="icon ml-0 salamat-light"><div class="material-icon">email</div></div>
                        <div class="text mx-0 salamat-color"><?= $model->user->email ?></div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-5">
                            <?= $form->field($model, 'phone')->textInput() ?>
                        </div>
                    </div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body pb-0">
                    <h5 class="card-title"><?= Yii::t('general', 'Dark theme') ?></h5>
                    <p class="card-text text-secondary"><?= Yii::t('general', 'Dark theme turns the light surfaces of the page dark, creating an experience ideal for night. Try it out!') ?></p>
                    <div class="row">
                        <div class="col-lg-3 col-md-4">        
                            <?= $form->field($model, 'dark_theme')->switch() ?>
                        </div>
                    </div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('user', 'Update profile'), [
                            'class' => "mdc-button btn-contained bg-salamat-color",
                        ]) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], [
                            'class' => "mdc-button btn-outlined salamat-color",
                        ]) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
