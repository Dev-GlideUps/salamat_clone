<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Branch;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */

$activeClinic = Yii::$app->user->identity->active_clinic;
$branches = Branch::find()->where(['clinic_id' => $activeClinic])->select('name')->indexBy('id')->column();
?>

<div class="col-auto align-self-center mb-3">
<?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
    'class' => 'mdc-button btn-outlined salamat-color',
    'data' => [
        'toggle' => 'modal',
        'target' => '#invoice-search',
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
                case 'branch_id': $value = $branches[$value]; break;

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

<div class="modal fade" id="invoice-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <?= $form->field($model, 'id')->textInput([
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'created_at')->textInput([
                            'class' => 'form-control bootstrap-datepicker',
                            'autocomplete' => 'off',
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
                <?php if (count($branches) > 1) {
                    echo $form->field($model, 'branch_id')->dropdownList($branches, [
                        'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]);
                } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
