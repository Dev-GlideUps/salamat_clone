<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Clinic */
/* @var $model clinic\models\Branch */

$this->title = Yii::t('clinic', 'Create Clinic');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Clinics / Hospitals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('general', 'Create');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card mt-5 clinic-create">
                <?php $form = ActiveForm::begin(); ?>
                <div class="card-body">
                    <h5 class="card-title mb-4"><?= $this->title ?></h5>

                    <div class="row">
                        <div class="col-sm-auto">
                            <div class="personal-photo-input square">
                                <?= $form->field($model, 'imageFile')->fileInput() ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <?= $form->field($model, 'name_alt')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-auto">
                                    <?= $form->field($model, 'phone')->textInput() ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'packageArray')->dropDownList($model::packages(), [
                                        "class" => "form-control bootstrap-select",
                                        "multiple" => true,
                                    ]) ?>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <?= $form->field($model, 'vat_account')->textInput() ?>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <?= $form->field($model, 'tax_account')->textInput() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mdc-divider"></div>

                <div class="card-body">
                    <h6 class="mb-4"><?= Yii::t('clinic', 'Branch Information') ?></h6>
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <?= $form->field($branch, 'name')->textInput() ?>
                        </div>
                        <div class="col-lg-6 col-md-7">
                            <?= $form->field($branch, 'address')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3">
                            <?= $form->field($branch, 'coordinatesInput[latitude]')->textInput()->label(Yii::t('general', 'Latitude')) ?>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <?= $form->field($branch, 'coordinatesInput[longitude]')->textInput()->label(Yii::t('general', 'Longitude')) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <?= $form->field($branch, 'phone')->textInput() ?>
                        </div>
                    </div>
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
