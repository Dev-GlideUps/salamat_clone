<?php

use yii\helpers\Html;
use common\grid\GridView;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\DiagnosisCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'ICD-10 Diagnosis codes');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
function setICD10UpdateForm(id, code, description) {
    $('#update-icd10-id').val(id);
    $('#update-icd10-code').val(code);
    $('#update-icd10-description').val(description);
}

function clearICD10UpdateForm() {
    $('#update-icd10-id').val('');
    $('#update-icd10-code').val('');
    $('#update-icd10-description').val('');
}
JS;

$this->RegisterJs($script, $this::POS_END);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?= $this->render('_search', [
            'model' => $searchModel,
        ]) ?>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <div class="mdc-button-group direction-reverse mx-1">
                    <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('general', 'Create new'), '#create-code', [
                        'class' => 'mdc-button salamat-color',
                        'data' => [
                            'toggle' => 'modal',
                        ],
                    ]) ?>
                </div>
                <div class="mdc-divider"></div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'code',
                        [
                            'attribute' => 'description',
                            'contentOptions' => ['style' => 'white-space: normal'],
                        ],
                        // 'created_at:date',
                        'updated_at:datetime',

                        [
                            'class' => 'common\grid\ActionColumn',
                            'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                            'template' => "{view}\n{update}\n{delete}\n",
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    $title = Yii::t('general', 'Update information');
                                    $options = [
                                        'title' => $title,
                                        'aria-label' => $title,
                                        'class' => 'mdc-list-item salamat-color',
                                        'onclick' => "setICD10UpdateForm($model->id, '$model->code', '$model->description');",
                                        'data-toggle' => 'modal',
                                    ];
                                    return Html::a(Html::tag('div', 'edit', ['class' => 'icon material-icon']).Html::tag('div', $title, ['class' => 'text']), "#update-code", $options);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create-code" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['create'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('patient', 'New diagnosis code') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($model, 'code')->textInput(['autocomplete' => 'off']) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 3, 'style' => 'resize: none;']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Create'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="update-code" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['update'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('patient', 'Update diagnosis code') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($model, 'id', ['selectors' => ['input' => '#update-icd10-id']])->hiddenInput(['id' => 'update-icd10-id']) ?>
                <?= $form->field($model, 'code', ['selectors' => ['input' => '#update-icd10-code']])->textInput(['id' => 'update-icd10-code', 'autocomplete' => 'off']) ?>
                <?= $form->field($model, 'description', ['selectors' => ['input' => '#update-icd10-description']])->textarea(['id' => 'update-icd10-description', 'rows' => 3, 'style' => 'resize: none;']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" onclick="clearICD10UpdateForm();" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>