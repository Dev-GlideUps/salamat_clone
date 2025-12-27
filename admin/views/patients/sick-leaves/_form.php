<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\SickLeave */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sick-leave-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'patient_id')->textInput() ?>

    <?= $form->field($model, 'appointment_id')->textInput() ?>

    <?= $form->field($model, 'leave_type')->textInput() ?>

    <?= $form->field($model, 'advise')->textInput() ?>

    <?= $form->field($model, 'days')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('patient', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
