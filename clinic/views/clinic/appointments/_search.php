<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Branch;
use clinic\models\Doctor;

/* @var $this yii\web\View */
/* @var $model clinic\models\AppointmentSearch */
/* @var $form yii\widgets\ActiveForm */

$activeClinic = Yii::$app->user->identity->active_clinic;
$branches = Branch::find()->where(['clinic_id' => $activeClinic])->select('name')->indexBy('id')->column();
$doctors = Doctor::find()->alias('doc')->joinWith(['branches b'])->where(['b.clinic_id' => $activeClinic])->select('doc.name')->indexBy('doc.id')->column();
?>

<div class="col-auto align-self-center mb-3">
    <div class="mdc-button-group py-0">
        <?= Html::a('view_column', ['index'], [
            'class' => 'material-icon mr-3',
        ]) ?>
        <?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
            'class' => 'mdc-button btn-outlined salamat-color',
            'data' => [
                'toggle' => 'modal',
                'target' => '#appointments-search',
            ],
        ]) ?>
    </div>
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
        if ($value !== null && strlen($value)) {
            $label = $model->getAttributeLabel($attribute);
            switch ($attribute) {
                case 'status': $value = $model::statusList()[$value]; break;
                case 'doctor_id': $value = $doctors[$value]; break;
                case 'branch_id': $value = $branches[$value]; break;

                default: break;
            }
            $string[] = "$label: <b>$value</b>";
        }
    }
    if (!empty($string)) { ?>
    <div class="alert alert-secondary show border mb-0 mt-3" role="alert">
        <?= implode(" - ", $string) ?>
        <?= Html::a('close', ['list'], [
            'class' => 'material-icon close',
        ]) ?>
    </div>
<?php } ?>
</div>

<div class="modal fade" id="appointments-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['list'],
            'method' => 'get',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
            </div>
            <div class="modal-body">
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
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date')->textInput([
                            'autocomplete' => 'off',
                            'class' => 'form-control bootstrap-datepicker',
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'status')->dropdownList($model::statusList(), [
                            'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                            'class' => 'form-control bootstrap-select',
                            // 'data-live-search' => 'true',
                        ]) ?>
                    </div>
                </div>
                <?php if (count($doctors) > 1) {
                    echo $form->field($model, 'doctor_id')->dropdownList($doctors, [
                        'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]);
                } ?>
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
