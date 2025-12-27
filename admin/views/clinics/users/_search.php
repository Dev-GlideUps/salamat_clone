<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-auto align-self-center mb-3">
<?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
    'class' => 'mdc-button btn-outlined salamat-color mt-5',
    'data' => [
        'toggle' => 'modal',
        'target' => '#branch-search',
    ],
]) ?>
</div>

<div class="col-12">
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
<?php } else { ?>
<div class="alert alert-secondary show border" role="alert">
    <?= implode(" - ", $string) ?>
    <?= Html::a('close', ['index'], [
        'class' => 'material-icon close',
    ]) ?>
</div>
<?php } ?>
</div>

<div class="modal fade" id="branch-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'modal-content'],
        'fieldConfig' => ['options' => ['class' => 'form-group']],
    ]); ?>

        <div class="modal-header">
            <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
        </div>
        <div class="modal-body mt-2 pb-0">

            <?= $form->field($model, 'clinic_id')->label(false)->dropDownList($clinics, [
                // 'placeholder' => $model->getAttributeLabel('clinic_id'),
                'title' => $model->getAttributeLabel('clinic_id'),
                'autocomplete' => 'off',
                'prompt' => ['text' => 'none', 'options' => ['class' => 'font-italic']],
                'class' => 'form-control bootstrap-select dense',
                'data-live-search' => 'true',
            ]) ?>
            <div class="row">
                <div class="col-3">
                    <?= $form->field($model, 'id')->label(false)->textInput([
                        'placeholder' => $model->getAttributeLabel('id'),
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'email')->label(false)->textInput([
                        'placeholder' => $model->getAttributeLabel('email'),
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'name')->label(false)->textInput([
                'placeholder' => $model->getAttributeLabel('name'),
                'autocomplete' => 'off',
            ]) ?>
            <div class="row">
                <div class="col-8">
                    <?= $form->field($model, 'phone')->label(false)->textInput([
                        'placeholder' => $model->getAttributeLabel('phone'),
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'created_at')->label(false)->textInput([
                        'placeholder' => $model->getAttributeLabel('created_at'),
                        'autocomplete' => 'off',
                        'class' => 'form-control bootstrap-datepicker',
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'updated_at')->label(false)->textInput([
                        'placeholder' => $model->getAttributeLabel('updated_at'),
                        'autocomplete' => 'off',
                        'class' => 'form-control bootstrap-datepicker',
                    ]) ?>
                </div>
            </div>
        
        </div>
        <div class="modal-footer">
            <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
            <?= Html::submitButton(Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
        </div>
    
    <?php ActiveForm::end(); ?>
    </div>
</div>

