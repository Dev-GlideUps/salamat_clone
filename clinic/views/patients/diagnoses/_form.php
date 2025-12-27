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

$('#diagnosis-description').on('change', function (event) {
    $('#diagnosis-code').val('');
});

function fill_diagnoses_description(code, description) {
    $('#diagnosis-code').val(code);
    $('#diagnosis-description').val(description);
}
function fill_diagnoses_notes_into_side_sheet(notes) {
    // console.log(notes);
    var notesArray = JSON.parse(atob(notes));
    $("#favorite-notes > .body.nano > .nano-content").children().remove();
    if (notesArray.length > 0) {
        notesArray.forEach(function (note, index) {
            var elm = `<button type="button" onclick="fill_diagnoses_notes('\${btoa(note)}')" class="card btn-block action-card p-0 mb-3" style="text-align: start;">
                <div class="card-body">\${note}</div>
            </button>`;
            $(elm).appendTo("#favorite-notes > .body.nano > .nano-content");
        });
    } else {
        var placeholder = `<p class="text-secondary">Can't find a note from the selected favorite diagnosis</p>`;
        $(placeholder).appendTo("#favorite-notes > .body.nano > .nano-content");
    }
    nano_scoller_init($("#favorite-notes > .body.nano"));
}
function fill_diagnoses_notes(note) {
    $('#diagnosis-notes').val(atob(note));
    // mdc_sheets_side_close($("#favorite-notes"));
}
function fill_from_fav(code, description, notes) {
    fill_diagnoses_description(code, description);
    fill_diagnoses_notes_into_side_sheet(notes);
}
JS;

$this->registerJs($script, $this::POS_END);
?>

<div class="card raised-card diagnosis-form">
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
    <div class="card-body pb-0">
        <div class="mdt-body mb-2"><?= Yii::t('patient', 'Diagnosis') ?></div>
        <div class="row">
            <div class="col-md-9">
                <?php
                $append = '<div class="input-group-append"><div class="mdc-button-group m-0"><button class="material-icon salamat-color mx-2" type="button" data-toggle="modal" data-target="#icd-10-modal">search</button></div></div>';
                if (!empty($favDiagnoses)) {
                    $append = '<div class="input-group-append" style="width: 5rem;"><div class="mdc-button-group m-0"><button class="material-icon salamat-color mx-2" type="button" data-toggle="modal" data-target="#fav-diagnoses">favorite_border</button><button class="material-icon salamat-color mx-2" type="button" data-toggle="modal" data-target="#icd-10-modal">search</button></div></div>';
                }
                echo $form->field($model, 'description')->textInput([
                    'maxlength' => true,
                    'autocomplete' => 'off',
                    'input-append' => $append,
                ]);
                ?>
            </div>
            <div class="col-md-3 col-sm-4">
                <?= $form->field($model, 'code')->textInput(['readonly' => true]) ?>
            </div>
        </div>
    </div>
    <div class="mdc-divider"></div>
    <div class="card-body">
        <?= $form->field($model, 'notes')->textarea(['rows' => 6, 'style' => 'resize: none;'])->hint(Yii::t('general', '* Optional')) ?>
        
        <?php if (!empty($favDiagnoses)) { ?>
        <div class="mdc-button-group direction-reverse p-0">
            <button type="button" class="mdc-button salamat-color" onclick="mdc_sheets_side_open('#favorite-notes');">
                <div class="icon material-icon">favorite_border</div>
                <?= Yii::t('general', 'Select note from favorites') ?>
            </button>
        </div>
        <?php } ?>
    </div>
    <div class="mdc-fab">
        <?= Html::submitButton(Html::tag('div', 'save', ['class' => 'icon material-icon']).Yii::t('patient', 'Save diagnosis'), ['class' => 'mdc-fab-button extended bg-salamat-color mb-3', 'name' => 'save']) ?>
        <div></div>
        <?= Html::submitButton(Html::tag('div', 'add_box', ['class' => 'icon material-icon']).Yii::t('patient', 'Save & add new diagnosis'), ['class' => 'mdc-fab-button extended bg-salamat-dark', 'name' => 'save_and_new']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="p-4"></div>

<div class="modal fade" id="fav-diagnoses" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('patient', 'Diagnoses favorite list') ?></div>
            </div>
            <div class="modal-body pb-0">
                <div class="search-results" style="margin: 0 -1.5rem;">
                    <div class="mdc-divider"></div>
                    <div class="mdc-list-group" style="max-height: 18rem; overflow-y: auto">
                        <?php foreach ($favDiagnoses as $favDiagnosis): ?>
                            <button type="button" class="mdc-list-item" onclick="fill_from_fav('<?= $favDiagnosis->code ?>', '<?= $favDiagnosis->description ?>', '<?= base64_encode($favDiagnosis->notes) ?>');" data-dismiss="modal">
                                <div class="text" style="white-space: normal;"><?= $favDiagnosis->description ?></div>
                                <div class="meta"><?= $favDiagnosis->code ?></div>
                            </button>
                        <?php endforeach; ?>
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

<div class="modal fade" id="icd-10-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('patient', 'Search ICD-10 diagnoses') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-label-group form-group mb-0">
                    <input type="text" class="form-control search-input" id="icd-10-search" autocomplete="off" placeholder="<?= Yii::t('patient', 'Enter keywords') ?>">
                    <label for="icd-10-search"><?= Yii::t('patient', 'Enter keywords') ?></label>
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

<div id="favorite-notes" class="mdc-sheets-side fixed">
    <div class="header">
        <div class="title"><?= Yii::t('clinic', 'Doctor notes') ?></div>
        <div class="close-action">
            <button class="material-icon">close</button>
        </div>
    </div>
    <div class="body nano">
        <div class="nano-content">
            <p class="text-secondary"><?= Yii::t('clinic', "Couldn't find notes for the selected diagnosis") ?></p>
        </div>
    </div>
</div>
<div class="mdc-sheets-side-scrim"></div>