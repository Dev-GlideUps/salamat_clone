<?php

use kartik\editors\Summernote;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$model->created_at=time();
$model->updated_at=time();
/* @var $this yii\web\View */
/* @var $model app\models\ConsentForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consent-form-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-6 col-md-8">
    <?= $form->field($model, 'name')->textInput() ?>

                        </div>
    <div class="col-lg-6 col-md-8">
    <?= $form->field($model, 'name_alt')->textInput(['maxlength' => true]) ?>

                        </div>
    <div class="col-lg-6 col-md-8">
    <?= $form->field($model, 'template_type')->textInput() ?>

                        </div>
                        <div class="col-lg-6 col-md-8">
                            <?= $form->field($model, 'clinic_id')->dropDownList($clinics, [
                                'autocomplete' => 'off',
                                'prompt' => ['text' => 'Select Clinic', 'options' => ['class' => '']],
                                'class' => 'form-control',
                                'data-live-search' => 'true',
                            ]) ?>
                        </div>

    <?php
    echo $form->field($model, 'content')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]);
    ?>


    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>

    <?php ActiveForm::end(); ?>

</div>
