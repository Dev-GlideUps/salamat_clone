<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model patient\models\PatientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-auto align-self-center mb-3">
    <div class="mdc-button-group py-0">
        <?= Html::a('cloud_download', ['export'], ['class' => 'material-icon mr-3']) ?>
        <?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
            'class' => 'mdc-button btn-outlined salamat-color',
            'data' => [
                'toggle' => 'modal',
                'target' => '#patients-search',
            ],
        ]) ?>
    </div>
</div>

<div class="col-12">
<?php
    $string = [];
    $attributes = array_merge([
        'profile_id' => $model->profile_id,
    ], $model->attributes);
    foreach($attributes as $attribute => $value) {
        if ($value !== null && strlen($value) > 0) {
            $label = $model->getAttributeLabel($attribute);
            switch ($attribute) {
                case 'name': $label = Yii::t('general', 'Name'); break;
                case 'profile_id': $label = Yii::t('patient', 'Profile ID'); break;

                default: break;
            }
            $string[] = "$label: <b>$value</b>";
        }
    }
    if (!empty($string)) { ?>
    <div class="alert alert-secondary show border mt-3" role="alert">
        <?= implode(" - ", $string) ?>
        <?= Html::a('close', ['index'], [
            'class' => 'material-icon close',
        ]) ?>
    </div>
<?php } ?>
</div>

<div class="modal fade" id="patients-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                <?= $form->field($model, 'profile_id')->textInput([
                    'autocomplete' => 'off',
                ])->label(Yii::t('patient', 'Profile ID')) ?>
                <?= $form->field($model, 'name')->textInput([
                    'autocomplete' => 'off',
                ])->label(Yii::t('general', 'Name')) ?>
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
