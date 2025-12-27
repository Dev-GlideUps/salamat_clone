<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\PatientExamNotes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card raised-card examination-notes-form">
    <div class="mdc-list-container">
        <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $patient->id]) ?>">
            <div class="graphic" style="background-image: url(<?= $patient->photoThumb ?>);"></div>
            <div class="text">
                <?= $patient->name ?>
                <div class="secondary"><?= $patient->name_alt ?></div>
            </div>
        </a>
    </div>
    <div class="mdc-divider"></div>

    <?php $form = ActiveForm::begin(); ?>
    <?php if (count($branches) > 1) { ?>
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'branch_id')->dropdownList($branches, [
                        'class' => 'form-control bootstrap-select',
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="mdc-divider"></div>
    <?php } ?>
    <div class="card-body">
        <?= $form->field($model, 'notes')->textarea(['rows' => 8, 'style' => 'resize: none;']) ?>
    </div>
    <div class="mdc-fab">
        <?= Html::submitButton(Html::tag('div', 'save', ['class' => 'icon material-icon']).Yii::t('patient', 'Save notes'), ['class' => 'mdc-fab-button extended bg-salamat-color mb-3', 'name' => 'save']) ?>
        <div></div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
