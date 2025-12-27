<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\DiagnosisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Favorite Diagnoses');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Diagnoses'), 'url' => ['/patients/diagnoses/index']];
$this->params['breadcrumbs'][] = Yii::t('general', 'Favorite');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/heart.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?php //= $this->render('_search', ['model' => $searchModel]); ?>
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
                <div class="card raised-card diagnosis-index">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'attribute' => 'description',
                                'label' => Yii::t('patient', 'Diagnosis'),
                                'contentOptions' => ['style' => 'white-space: normal'],
                            ],
                            'code',
                            [
                                'label' => Yii::t('clinic', 'Doctor notes'),
                                'value' => function ($model) {
                                    return Yii::t('clinic', '{count} notes', ['count' => count($model->notesArray)]);
                                },
                            ],
                            'created_at:date',
                            //'updated_at',
                            //'created_by',
                            //'updated_by',
                            [
                                'class' => 'common\grid\ActionColumn',
                            ],
                        ],
                    ]); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="mdc-fab">
    <?= Html::a(
        Html::tag('div', 'add', ['class' => 'icon material-icon']) .
        Yii::t('general', 'New favorite'), ['create'], [
        'class' => 'mdc-fab-button extended bg-salamat-color',
    ]) ?>
</div>