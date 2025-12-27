<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Clinic;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = Yii::t('user', 'New User');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('user', 'Register');

$clinics = Clinic::find()->select('name')->indexBy('id')->column();
?>

<div class="container-custom user-create">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <?= $form->field($model, 'clinic_id')->dropDownList($clinics, [
                                'autocomplete' => 'off',
                                'prompt' => ['text' => '', 'options' => ['class' => 'd-none']],
                                'class' => 'form-control bootstrap-select',
                                'data-live-search' => 'true',
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'email')->textInput() ?>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-auto">
                            <?= $form->field($model, 'phone')->textInput() ?>
                        </div>
                    </div>
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('user', 'Register'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
