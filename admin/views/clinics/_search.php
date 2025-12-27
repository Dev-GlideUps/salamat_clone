<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\ClinicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clinic-search py-2 px-3">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig' => ['options' => ['class' => 'form-group my-1']],
    ]); ?>

    <div class="row align-items-center mx-n2">
        <div class="col-md-1 col-sm-2 col-3 order-1 px-2">
            <?= $form->field($model, 'id')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('id'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 order-sm-2 order-3 px-2">
            <?= $form->field($model, 'name')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('name'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-md-3 col-sm-4 col-9 order-sm-3 order-2 px-2">
            <?= $form->field($model, 'phone')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('phone'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col order-4 px-2">
            <?= $form->field($model, 'created_at')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('created_at'),
                'autocomplete' => 'off',
                'class' => 'form-control bootstrap-datepicker',
            ]) ?>
        </div>
        <div class="col order-5 px-2">
            <?= $form->field($model, 'updated_at')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('updated_at'),
                'autocomplete' => 'off',
                'class' => 'form-control bootstrap-datepicker',
            ]) ?>
        </div>
    </div>
    <div class="mdc-button-group direction-reverse">
        <?= Html::submitButton(Html::tag('div', 'search', ['class' => 'material-icon icon']).Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$string = [];
foreach($model->attributes as $attribute => $value) {
    if (!empty($value)) {
        $string[] = $model->getAttributeLabel($attribute).": <b>$value</b>";
    }
}
if (empty($string)) { ?>
<div class="mdc-divider"></div>
<?php } else { ?>
<div class="alert alert-secondary show border rounded-0 mb-0" role="alert">
    <?= implode(" - ", $string) ?>
    <?= Html::a('close', ['index'], [
        'class' => 'material-icon close',
    ]) ?>
</div>
<?php } ?>
