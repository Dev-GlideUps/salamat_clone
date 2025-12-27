<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\MedicineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Medicines');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php if (count($dataProvider->models) == 0) { ?>
                <div class="text-center">
                    <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                        <h5 class="text-hint my-3"><?= Yii::t('general', 'No results found') ?></h5>
                    </div>
                </div>
            <?php } else { ?>
            <div class="card raised-card">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    // ['class' => 'common\grid\SerialColumn'],

                    // 'id',
                    // 'clinic_id',
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
                    // 'created_at',
                    // 'updated_at',
                    // 'created_by',
                    // 'updated_by',
        
                    [
                        'class' => 'common\grid\ActionColumn',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('general', 'Update information'),
                                    'aria-label' => Yii::t('general', 'Update information'),
                                    'onclick' => "setUpdateMedicineForm({$model->id}, '{$model->name}', {$model->forms});",
                                    'class' => 'material-icon mx-2',
                                    'data-toggle' => 'modal',
                                ];
                                return Html::a("edit", "#update-medicine", $options);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('clinic', 'New medicine'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-medicine',
        ],
    ]) ?>
</div>

<div class="modal fade" id="add-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['create'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'New medicine') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
                <?= $form->field($model, 'formats')->checkboxList($model::formList()) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Add medicine'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
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
                <?= $form->field($model, 'id', ['selectors' => ['input' => '#update-medicine-id']])->hiddenInput(['id' => 'update-medicine-id']) ?>
                <?= $form->field($model, 'name', ['selectors' => ['input' => '#update-medicine-name']])->textInput(['id' => 'update-medicine-name', 'autocomplete' => 'off']) ?>
                <?= $form->field($model, 'formats', ['selectors' => ['input' => '#update-medicine-formats']])->checkboxList($model::formList(), ['id' => 'update-medicine-formats']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('clinic', 'Update medicine'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>