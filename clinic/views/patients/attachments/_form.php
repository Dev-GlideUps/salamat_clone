<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model patient\models\Attachment */
/* @var $form yii\widgets\ActiveForm */

$script = <<< JS
$('#attachment-attachmentfile').parent('.custom-file').append('<div class="custom-file-path"></div>');
$('#attachment-attachmentfile').on('change', function() {
    var fileName = this.files[0].name;
    $(this).siblings('.custom-file-path').html(fileName);
})
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="card raised-card prescription-form">
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

    <?php $form = ActiveForm::begin(['id' => 'attachment-form']); ?>

    <div class="card-body pb-0">
        <?php if (count($branches) > 1) { ?>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <?= $form->field($model, 'branch_id')->dropdownList($branches, [
                    'class' => 'form-control bootstrap-select',
                ]) ?>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-4 col-md-5">
                <?= $form->field($model, 'category_id')->dropdownList($categories, [
                    'class' => 'form-control bootstrap-select',
                    'prompt' => ['text' => '...', 'options' => ['class' => 'font-italic d-none']],
                    'data-live-search' => 'true',
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-5">
                <?= $form->field($model, 'attachmentFile')->fileInput() ?>
            </div>
        </div>
    </div>

    <div class="mdc-fab">
        <?= Html::button(Html::tag('div', 'attach_file', ['class' => 'icon material-icon']).Yii::t('general', 'Add attachment'), [
            'class' => 'mdc-fab-button extended bg-salamat-color d-none',
            'data' => [
                'toggle' => 'modal',
                'target' => '#add-new-attachment',
            ],
        ]) ?>
        <?= Html::submitButton(Html::tag('div', 'attach_file', ['class' => 'icon material-icon']).Yii::t('general', 'Add attachment'), [
            'class' => 'mdc-fab-button extended bg-salamat-color',
        ]) ?>
    </div>

    <div class="modal fade" id="add-new-attachment" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"><?= Yii::t('finance', 'Create new prescription') ?></div>
                </div>
                <div class="modal-body">
                    <?= Yii::t('finance', 'Prescriptions cannot be updated or changed after creation. please double check prescription information before creating it.') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                    <?= Html::submitButton(Yii::t('general', 'Create'), ['class' => 'mdc-button salamat-color', 'form' => 'attachment-form']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
