<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\BranchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branch-search py-2 px-3">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig' => ['options' => ['class' => 'form-group my-1']],
    ]); ?>

    <div class="row align-items-center mx-n2">
        <div class="col-md-1 col-sm-2 col-3 px-2">
            <?= $form->field($model, 'id')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('id'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-md-3 col-sm-4 col-9 px-2">
            <?= $form->field($model, 'clinic_id')->label(false)->dropDownList($clinics, [
                // 'placeholder' => $model->getAttributeLabel('clinic_id'),
                'title' => $model->getAttributeLabel('clinic_id'),
                'autocomplete' => 'off',
                'prompt' => ['text' => 'none', 'options' => ['class' => 'font-italic']],
                'class' => 'form-control bootstrap-select dense',
                'data-live-search' => 'true',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 px-2">
            <?= $form->field($model, 'name')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('name'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-md-4 px-2">
            <?= $form->field($model, 'address')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('address'),
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-auto px-2">
            <?= $form->field($model, 'created_at')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('created_at'),
                'autocomplete' => 'off',
                'class' => 'form-control bootstrap-datepicker',
            ]) ?>
        </div>
        <div class="col-auto px-2">
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
        if ($attribute == 'clinic_id') {
            $value = $clinics[$value];
        }
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
