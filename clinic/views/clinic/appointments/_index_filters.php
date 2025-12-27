<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\AppointmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-auto align-self-center">
    <div class="mdc-button-group py-0">
        <?= Html::a('view_list', ['list'], [
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

<div class="modal fade" id="appointments-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
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
                <?php if (count($branches) > 1) {
                    echo $form->field($model, 'branch_id')->dropdownList($branches, [
                        'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]);
                } ?>
                <?= $form->field($model, 'date')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                ]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
