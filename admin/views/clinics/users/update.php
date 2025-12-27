<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Update');
?>

<div class="container-custom user-update">
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
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'email')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-auto">
                            <?= $form->field($model, 'phone')->textInput() ?>
                        </div>
                    </div>
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
