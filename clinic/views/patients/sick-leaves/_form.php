<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card raised-card diagnosis-form">
    <div class="mdc-list-container">
        <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $diagnosis->patient->id]) ?>">
            <div class="graphic" style="background-image: url(<?= $model->patient->photoThumb ?>);"></div>
            <div class="text">
                <?= $diagnosis->patient->name ?>
                <div class="secondary"><?= $diagnosis->patient->name_alt ?></div>
            </div>
        </a>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body">
        <div class="mdt-subtitle-2 mb-2"><?= Yii::t('patient', 'Diagnosis') ?></div>
        <div class="mdt-body">
            <?php if (!empty($diagnosis->code)) { ?>
            <span class="text-secondary">(<?= $diagnosis->code ?>)</span>
            <?php } ?>
            <?= $diagnosis->description ?>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body pb-0">
        <div class="row">
            <div class="col-auto pt-1">
                <?= $form->field($model, 'leave_type')->radioList($model::typeList())->label(false) ?>
            </div>
            <div class="col-lg-5 col-md-6">
                <?= $form->field($model, 'advise')->dropdownList($model::adviseList(), [
                    'class' => 'form-control bootstrap-select',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-4">
                <?= $form->field($model, 'commencing_on')->textInput([
                    'autocomplete' => 'off',
                    'class' => 'form-control bootstrap-datepicker',
                    'data-date-start-date' => date('Y-m-d'),
                    // 'data-date-end-date' => date('Y-m-d', strtotime('+5 days')),
                ]) ?>
            </div>
            <div class="col-md-auto">
                <?= $form->field($model, 'days')->textInput(['autocomplete' => 'off']) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="mdc-button-group direction-reverse p-3">
        <?= Html::submitButton(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('general', 'Create sick leave'), ['class' => 'mdc-button salamat-color']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
