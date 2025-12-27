<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\DiagnosisCodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-auto align-self-center mb-3">
<?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
    'class' => 'mdc-button btn-outlined salamat-color mt-5',
    'data' => [
        'toggle' => 'modal',
        'target' => '#grid-rows-search',
    ],
]) ?>
</div>

<div class="col-12">
<?php
$string = [];
foreach($model->attributes as $attribute => $value) {
    if (!empty($value)) {
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

<div class="modal fade" id="grid-rows-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'modal-content'],
    ]); ?>

        <div class="modal-header">
            <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
        </div>
        <div class="modal-body mt-2 pb-0">

            <div class="row">
                <div class="col-5">
                    <?= $form->field($model, 'id')->textInput([
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'code')->textInput([
                        'autocomplete' => 'off',
                    ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->textInput([
                'autocomplete' => 'off',
            ]) ?>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'created_at')->textInput([
                        'autocomplete' => 'off',
                        'class' => 'form-control bootstrap-datepicker',
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'updated_at')->textInput([
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