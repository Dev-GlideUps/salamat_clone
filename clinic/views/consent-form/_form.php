<?php

use kartik\editors\Summernote;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConsentForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consent-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'name_alt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'template_type')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?php
    echo $form->field($model, 'content')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]);
    ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

  
    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::a(Yii::t('general', 'Cancel'), ['index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
                    </div>  

    <?php ActiveForm::end(); ?>

</div>
