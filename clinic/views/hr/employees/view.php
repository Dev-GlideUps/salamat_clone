<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\hr\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('hr', 'Human resources');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= $this->title ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card raised-card employee-view">
        <div class="card-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'phone',
                'cpr',
                'cpr_expiry:date',
                'address',
                [
                    'attribute' => 'salary',
                    'format' => ['decimal', 3],
                ],
                'contract_start:date',
                'contract_expiry:date',
                [
                    'attribute' => 'nationality',
                    'value' => function ($model) {
                        $country = new \common\models\Country();
                        return $country->countriesList[$model->nationality];
                    },
                ],
                'passport_start:date',
                'passport_expiry:date',
                'visa_expiry:date',
                'residency_start:date',
                'residency_expiry:date',
                [
                    'attribute' => 'created_by',
                    'value' => function ($model) {
                        return $model->creator->email;
                    },
                ],
                'created_at:datetime',
                [
                    'attribute' => 'updated_by',
                    'value' => function ($model) {
                        return $model->updater->email;
                    },
                ],
                'updated_at:datetime',
            ],
        ]) ?>
        </div>
        <div class="mdc-divider"></div>
        <div class="mdc-button-group direction-reverse p-3">
            <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'mdc-button salamat-color',
            ]) ?>
            <?= Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                'class' => 'mdc-button salamat-color',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#delete-record',
                ],
            ]) ?>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-record" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['delete'], 'post', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Delete record') ?></div>
            </div>
            <input type="hidden" class="record-id" name="id" value="<?= $model->id ?>">
            <div class="modal-body pb-0">
                <?= Yii::t('general', 'The selected record will be deleted permanently. This action cannot be undone.') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Delete'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?= Html::endForm() ?>
    </div>
</div>