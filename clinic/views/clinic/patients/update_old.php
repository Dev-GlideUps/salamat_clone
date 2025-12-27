<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model patient\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Patients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('patient', 'Update');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="patient-update">

                <div class="patient-form">
                    <div class="card raised-card mb-4">
                        <?php $form = ActiveForm::begin(); ?>
                            <div class="card-body">
                                <h6 class="mb-3"><?= Yii::t('patient', 'Patient information') ?></h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="text-secondary" style="width: 25%;"><?= $model->getAttributeLabel('cpr') ?></td>
                                            <td><?= $model->cpr ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary"><?= $model->getAttributeLabel('name') ?></td>
                                            <td><?= $model->name ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary"><?= $model->getAttributeLabel('name_alt') ?></td>
                                            <td><?= $model->name_alt ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary"><?= $model->getAttributeLabel('dob') ?></td>
                                            <td><?= $model->dob === null ? '' : Yii::$app->formatter->asDate($model->dob, 'long') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary"><?= Yii::t('general', 'Age') ?></td>
                                            <td><?= $model->getAge(true) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary"><?= $model->getAttributeLabel('gender') ?></td>
                                            <td><?= $model->gender === null ? '' : $model::genderList()[$model->gender] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="mdc-divider"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3">
                                        <?= $form->field($model->clinicPatient, 'profile_ref')->textInput(['autocomplete' => 'off']) ?>
                                    </div>
                                    <div class="col-lg-6 col-md-8">
                                        <?= $form->field($model, 'address')->textInput(['autocomplete' => 'off']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <?= $form->field($model, 'phone')->textInput(['autocomplete' => 'off']) ?>
                                    </div>
                                    <div class="col-lg-2 col-md-3">
                                        <?= $form->field($model, 'height')->textInputTextAppend(['autocomplete' => 'off', 'text-append' => Yii::t('general', 'cm')]) ?>
                                    </div>
                                    <div class="col-lg-2 col-md-3">
                                        <?= $form->field($model, 'weight')->textInputTextAppend(['autocomplete' => 'off', 'text-append' => Yii::t('general', 'Kg')]) ?>
                                    </div>
                                </div>
                                <div class="mdc-button-group direction-reverse p-0">
                                    <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button salamat-color']) ?>
                                    <?= Html::a(Yii::t('general', 'Cancel'), ['view', 'id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
