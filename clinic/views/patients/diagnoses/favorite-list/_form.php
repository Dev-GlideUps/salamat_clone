<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */
/* @var $form yii\widgets\ActiveForm */

$ajaxUrl = Url::to(['/patients/diagnoses/search-icd10']);
$csrfToekn = Yii::$app->request->getCsrfToken();
$script = <<< JS
$('#icd-10-search').on('keypress', function (event) {
    if (event.which == 13) {
        $('#icd-10-modal .search-results').html('<div class="mdc-progress-track salamat-color"><div class="indicator indeterminate"></div></div>');

        $.ajax({
            url: '$ajaxUrl',
            type: 'post',
            data: {
                search: $('#icd-10-search').val(),
                _csrf: '$csrfToekn'
            },
            success: function (data) {
                $('#icd-10-modal .search-results').html(data);
            },
            error: function (data) {
                $('#icd-10-modal .search-results').html('<div class="mdt-subtitle-2 text-secondary">Couldn\'t find any results!</div>');
            }
        });
    }
});

$('#favoritediagnosis-description').on('change', function (event) {
    $('#favoritediagnosis-code').val('');
});

function fill_diagnoses_description(code, description) {
    $('#favoritediagnosis-code').val(code);
    $('#favoritediagnosis-description').val(description);
}
function add_note() {
    var index = $("#notesContainer").children().length;
    var elm  = `<div class="form-group field-favoritediagnosis-notesarray-\${index}">
<label for="favoritediagnosis-notesarray-\${index}">Doctor notes \${index + 1}</label>
<textarea id="favoritediagnosis-notesarray-\${index}" class="form-control" name="FavoriteDiagnosis[notesArray][\${index}]" rows="3" style="resize: none;"></textarea>
<div class="invalid-feedback"></div>
<small class="form-text text-muted">* Optional</small>
</div>`;
    var delBtn  = `<button type="button" class="mdc-button salamat-color" onclick="delete_note(\${index})"><div class="icon material-icon">close</div>Delete</button>`;
    var html = `<div data-index="\${index}" class="row d-flex align-items-center"><div class="col-md-9">\${elm}</div><div class="col-md-3">\${delBtn}</div></div>`;
    $( html ).appendTo( "#notesContainer" );
}
function delete_note(index) {
    $('div[data-index="' + index + '"]').remove();
}
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="card raised-card favorite-diagnosis-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body pb-0">
        <div class="mdt-body mb-2"><?= Yii::t('clinic', 'Favorite Diagnosis') ?></div>
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'description')->textInput([
                    'maxlength' => true,
                    'autocomplete' => 'off',
                    'input-append' => '<div class="input-group-append"><div class="mdc-button-group m-0"><button class="material-icon salamat-color mx-2" type="button" data-toggle="modal" data-target="#icd-10-modal">search</button></div></div>'
                ]) ?>
            </div>
            <div class="col-md-3 col-sm-4">
                <?= $form->field($model, 'code')->textInput(['readonly' => true]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div id="notesContainer" class="card-body">
        <?php if($model->isNewRecord || empty($model->notesArray)): ?>
            <div  class="row d-flex align-items-center">
                <div class="col-md-9">
                    <?= $form->field($model, 'notesArray[0]')
                        ->textarea(['rows' => 3, 'style' => 'resize: none;'])
                        ->label('Doctor notes 1')
                        ->hint(Yii::t('general', '* Optional')) ?>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        <?php else: ?>
        <?php foreach ($model->notesArray as $index => $note): ?>
            <div data-index="<?= $index ?>" class="row d-flex align-items-center">
                <div class="col-md-9">
                    <?= $form->field($model, "notesArray[$index]")
                        ->textarea(['rows' => 3, 'style' => 'resize: none;'])
                        ->label("Doctor notes " . ($index + 1))
                        ->hint(Yii::t('general', '* Optional')) ?>
                </div>
                <div class="col-md-3">
                    <?php if ($index > 0): ?>
                        <?= Html::button(Html::tag('div', 'close', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                            'class' => 'mdc-button salamat-color',
                            'onclick' => "delete_note($index)",
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
<?php endif; ?>
</div>
    <div class="card-footer">
        <div class="mdc-button-group direction-reverse">
            <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('clinic', 'Doctor note'), [
                'class' => 'mdc-button salamat-color',
                'onclick' => 'add_note()',
            ]) ?>
        </div>
    </div>

<div class="mdc-fab">
    <?= Html::submitButton(Html::tag('div', 'save', ['class' => 'icon material-icon']).Yii::t('general', 'Save'), ['class' => 'mdc-fab-button extended bg-salamat-color']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>

<div class="modal fade" id="icd-10-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Search ICD-10 diagnoses') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-label-group form-group mb-0">
                    <input type="text" class="form-control search-input" id="icd-10-search" autocomplete="off" placeholder="<?= Yii::t('clinic', 'Enter keywords') ?>">
                    <label for="icd-10-search"><?= Yii::t('clinic', 'Enter keywords') ?></label>
                    <small class="form-text text-secondary"><?= Yii::t('general', 'Note: press ENTER key to search') ?></small>
                </div>
                <div class="search-results" style="margin: 0 -1.5rem;">
                    <div class="mdc-divider" style="margin-top: 0.5rem;"></div>
                    <div class="mdc-list-group bg-salamat-secondary" style="max-height: 14rem; overflow-y: auto">
                        <button type="button" class="mdc-list-item" onclick="fill_diagnoses_description('', 'Unspecified');" data-dismiss="modal">
                            <div class="text" style="white-space: normal;">Unspecified</div>
                        </button>
                    </div>
                    <div class="mdc-divider"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
            </div>
        </div>
    </div>
</div>
