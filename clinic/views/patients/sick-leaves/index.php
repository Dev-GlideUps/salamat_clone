<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\SickLeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'Sick leaves');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?= $this->render('_search', ['model' => $searchModel]); ?>
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
            <div class="card raised-card sick-leave-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'patient_id',
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
                        'attribute' => 'leave_type',
                        'value' => function ($model) {
                            return $model->typeList()[$model->leave_type];
                        },
                    ],
                    [
                        'attribute' => 'advise',
                        'value' => function ($model) {
                            return $model->adviseList()[$model->advise];
                        },
                    ],
                    [
                        'attribute' => 'days',
                        'value' => function ($model) {
                            return Yii::t('general', '{n} Days', ['n' => $model->days]);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date'],
                    ],
                    //'updated_at',
                    //'created_by',
                    //'updated_by',
    
                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{pdf}\n{view}",
                        'buttons' => [
                            'pdf' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('general', 'PDF export'),
                                    'aria-label' => Yii::t('general', 'PDF export'),
                                    'target' => '_blank',
                                    'class' => 'material-icon mx-2',
                                ];
                                return Html::a("picture_as_pdf", $url, $options);
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
