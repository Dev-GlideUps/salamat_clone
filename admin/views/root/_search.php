<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\ClinicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clinic-search py-2 px-3">
    <?php $form = ActiveForm::begin([
        'action' => ['sms-log'],
        'method' => 'get',
        'fieldConfig' => ['options' => ['class' => 'form-group my-1']],
    ]); ?>

    <div class="row align-items-center mx-n2">


        <div class="col-3 order-1 px-2">
            <?= $form->field($model, 'created_at')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('Date'),
                'autocomplete' => 'off',
                'class' => 'form-control bootstrap-monthpicker',
                // 'Options' => [
                //     'autoclose' => true,
                //     // 'format' => 'M-yyyy'
                //     'class' => 'form-control bootstrap-datepicker',
                // ]
            ]) ?>
            
        </div>
        <div class="mdc-button-group order-2 col-4">
            <?= Html::submitButton(Html::tag('div', 'search', ['class' => 'material-icon icon']) . Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
        </div>
        


    </div>


    <?php ActiveForm::end(); ?>
</div>

<?php
$string = [];
foreach ($model->attributes as $attribute => $value) {
    if (!empty($value)) {
        // print_r($value);
        $string[] = $model->getAttributeLabel($attribute) . ": <b>" . date('Y-m-d H:i:s', $value) . "</b>";
    }
}
if (empty($_GET['AppointmentSms']['created_at'])) { ?>
    <div class="mdc-divider"></div>
<?php } else {
    
    ?>
    <div class="alert alert-secondary show border rounded-0 mb-0" role="alert">
        <?= implode(" - ", array('Created At : <b>' . date('F Y', strtotime($_GET['AppointmentSms']['created_at'])). "</b>")) ?>
        <?= Html::a('close', ['sms-log'], [
            'class' => 'material-icon close',
        ]) ?>
    </div>
<?php } ?>