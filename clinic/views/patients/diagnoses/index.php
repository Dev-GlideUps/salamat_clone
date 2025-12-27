<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\DiagnosisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'Diagnoses');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <div class="col-auto align-self-center mb-3">
            <?= Html::a(Html::tag('div', 'favorite_border', ['class' => 'icon material-icon']).Yii::t('general', 'Favorite list'), ['/patients/diagnoses/favorite-list/index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
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
                        'label' => Yii::t('patient', 'Patient'),
                        'value' => function ($model) {
                            return Html::tag('div',
                                Html::tag('div', '', ['class' => 'graphic m-0','style' => "background-image: url('{$model->patient->photoThumb}');"]).
                                Html::tag('div',
                                    $model->patient->name.
                                    Html::tag('div', $model->patient->name_alt, ['class' => 'secondary']),
                                ['class' => 'text my-0 mr-0']),
                            ['class'=> 'mdc-list-item']);
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'description',
                        'label' => Yii::t('patient', 'Diagnosis'),
                        'contentOptions' => ['style' => 'white-space: normal'],
                    ],
                    'code',
                    'created_at:date',
                    //'updated_at',
                    //'created_by',
                    //'updated_by',

                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{view}",
                    ],
                ],
            ]); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
