<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Speciality */

$this->title = $model->title;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Specialities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'title',
                            'title_ar',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                            'class' => 'mdc-button btn-outlined salamat-color',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#delete-record',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-record" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Delete speciality') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('general', 'The record will be deleted from the database. this action cannot be undone.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a(Yii::t('clinic', 'Delete speciality'), ['delete', 'id' => $model->id], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>
