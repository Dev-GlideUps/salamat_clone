<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoicePaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-auto align-self-center mb-3">
<?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
    'class' => 'mdc-button btn-outlined salamat-color',
    'data' => [
        'toggle' => 'modal',
        'target' => '#payment-search',
    ],
]) ?>
</div>

<div class="col-12">
<?php
    $string = [];
    $attributes = array_merge([
        'cpr' => $model->cpr,
        'name' => $model->name,
        'phone' => $model->phone,
    ], $model->attributes);
    foreach($attributes as $attribute => $value) {
        if ($value !== null && strlen($value) > 0) {
            $label = $model->getAttributeLabel($attribute);
            switch ($attribute) {
                case 'payment_method': $value = $model::methodList()[$value]; break;

                default: break;
            }
            $string[] = "$label: <b>$value</b>";
        }
    }
    if (!empty($string)) { ?>
    <div class="alert alert-secondary show border" role="alert">
        <?= implode(" - ", $string) ?>
        <?= Html::a('close', ['index'], [
            'class' => 'material-icon close',
        ]) ?>
    </div>
<?php } ?>
</div>

<div class="modal fade" id="payment-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'invoice_id')->textInput([
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'id')->textInput([
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'created_at')->textInput([
                            'class' => 'form-control bootstrap-datepicker',
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'payment_method')->dropdownList($model::methodList(), [
                            'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                            'class' => 'form-control bootstrap-select',
                            // 'data-live-search' => 'true',
                        ]) ?>
                    </div>
                </div>
                <?= $form->field($model, 'name')->textInput([
                    'autocomplete' => 'off',
                ]) ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'cpr')->textInput([
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'phone')->textInput([
                            'autocomplete' => 'off',
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
