<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Medicine */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Medicines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
?>

<div class="container-custom appointment-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/bottle_1.svg')) ?>
                </div>
                <div class="media-body salamat-color">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'name',
                            [
                                'attribute' => 'formats',
                                'value' => function ($model) {
                                    $forms = [];
                                    foreach ($model->formats as $item) {
                                        $forms[] = $model::formList()[$item];
                                    }
                                    return implode(', ', $forms);
                                },
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mdt-subtitle-2 text-secondary mb-2"><?= $model->getAttributeLabel('created_by') ?></div>
                            <?= Html::beginTag('a', ['class' => 'mdc-list-item', 'href' => Url::to(['/clinic/users/view', 'id' => $model->created_by])]).
                                Html::tag('div', 'person', ['class' => 'graphic material-icon bg-salamat-color']).
                                Html::beginTag('div', ['class' => 'text']).
                                    $model->creator->email.
                                    Html::tag('div', $formatter->asDateTime($model->created_at), ['class' => 'secondary']).
                                Html::endTag('div').
                            Html::endTag('a') ?>
                        </div>
                        <div class="col-lg-6">
                            <div class="mdt-subtitle-2 text-secondary mb-2"><?= $model->getAttributeLabel('updated_by') ?></div>
                            <?= Html::beginTag('a', ['class' => 'mdc-list-item', 'href' => Url::to(['/clinic/users/view', 'id' => $model->updated_by])]).
                                Html::tag('div', 'person', ['class' => 'graphic material-icon bg-salamat-color']).
                                Html::beginTag('div', ['class' => 'text']).
                                    $model->updater->email.
                                    Html::tag('div', $formatter->asDateTime($model->updated_at), ['class' => 'secondary']).
                                Html::endTag('div').
                            Html::endTag('a') ?>
                        </div>
                    </div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <div class="mdc-button-group direction-reverse py-0">
                        <?= Html::button(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), [
                            'class' => 'mdc-button salamat-color',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#update-medicine',
                            ],
                        ]) ?>
                        <?= Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                            'class' => 'mdc-button salamat-color',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#delete-medicine',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['update'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Update medicine') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($model, 'id')->hiddenInput() ?>
                <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
                <?= $form->field($model, 'formats')->checkboxList($model::formList()) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Update medicine'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="delete-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['delete'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Delete record') ?></div>
            </div>
            <input type="hidden" class="record-id" name="id" value="<?= $model->id ?>">
            <div class="modal-body"><?= Yii::t('general', 'The selected record will be deleted permanently. This action cannot be undone.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Delete'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
