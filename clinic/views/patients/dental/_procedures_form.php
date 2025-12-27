<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\dental\Category;

$categories = Category::find()->alias('cat')->joinWith(['procedures proc'])->where(['status' => Category::STATUS_ACTIVE])->orderBy(['cat.title' => SORT_ASC, 'proc.description' => SORT_ASC])->all();

$script = <<< JS
    $('#procedures-form-tabs .nav-link').first().click();

    $('#procedures-form-panes').on('click', 'button.btn-procedure', function () {
        if ($(this).hasClass('salamat-color')) {
            return;
        }
        $('#procedures-form-panes button.btn-procedure.salamat-color').removeClass('salamat-color').children('.icon').text('radio_button_unchecked');

        var id = $(this).attr('data-procedure');
        $('#record-procedure_id').val(id).trigger('change');

        $(this).addClass('salamat-color').children('.icon').text('radio_button_checked');
    });
JS;
$this->registerJs($script, $this::POS_END);
?>

<ul class="nav nav-tabs" id="procedures-form-tabs" role="tablist">
    <?php foreach ($categories as $cat) { ?>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#cat-procedures-<?= $cat->id ?>" role="tab" aria-selected="false"><?= $cat->title ?></a>
    </li>
    <?php } ?>
</ul>

<div class="tab-content" id="procedures-form-panes">
    <?php foreach ($categories as $cat) { ?>
    <div class="tab-pane p-4 fade" id="cat-procedures-<?= $cat->id ?>" role="tabpanel">
        <h5><?= $cat->title ?></h5>
        <div class="row mdc-list-container">
            <?php foreach ($cat->procedures as $proc) { ?>
            <div class="col-lg-4 col-md-6">
                <button type="button" class="btn-procedure mdc-list-item" data-procedure="<?= $proc->id ?>">
                    <div class="icon material-icon">radio_button_unchecked</div>
                    <div class="text" style="white-space: normal;"><?= $proc->description ?></div>
                </button>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<div class="mdc-divider"></div>

<?php $form = ActiveForm::begin(['id' => 'new-procedure-form', 'options' => ['class' => 'p-4']]); ?>
<?= $form->field($model, 'procedure_id')->textInput(['class' => 'd-none'])->label(false) ?>
<div class="row">
    <?php if (count($branches) > 1) { ?>
    <div class="col-lg-4 col-md-6">
        <?= $form->field($model, 'branch_id')->dropdownList($branches, [
            'class' => 'form-control bootstrap-select',
        ]) ?>
    </div>
    <?php } ?>
    <div class="col-lg-3 col-md-4">
        <?= $form->field($model, 'procedure_date')->textInput([
            'autocomplete' => 'off',
            'class' => 'form-control bootstrap-datepicker',
            'data-date-start-date' => date('Y-m-d'),
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-10">
        <?= $form->field($model, 'notes')->textarea(['rows' => 6, 'style' => 'resize: none;'])->hint(Yii::t('general', '* Optional')) ?>
    </div>
</div>
<div class="mdc-fab">
    <?= Html::submitButton(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('patient', 'Add procedure'), [
        'class' => 'mdc-fab-button extended bg-salamat-color no-dismiss',
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
