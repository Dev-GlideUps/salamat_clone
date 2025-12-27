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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
