<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\SickLeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'Sick Leaves');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients data');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?php /*= $this->render('_search', [
            'model' => $searchModel,
        ])*/ ?>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'patient_id',
                            'value' => function ($model) {
                                return Html::tag('div',
                                    Html::tag('div', 'person', ['class' => 'graphic material-icon bg-salamat-color m-0']).
                                    Html::tag('div',
                                        $model->patient->name.
                                        Html::tag('div', $model->patient->name_alt, ['class' => 'secondary']),
                                    ['class' => 'text my-0 mr-0']),
                                ['class'=> 'mdc-list-item']);
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'advise',
                            'value' => function ($model) {
                                return $model::adviseList()[$model->advise];
                            },
                        ],
                        [
                            'attribute' => 'leave_type',
                            'value' => function ($model) {
                                return $model::typeList()[$model->leave_type];
                            },
                        ],
                        'days',
                        'created_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => function ($model) {
                                return $model->doctor->name;
                            },
                        ],
                        //'updated_at',
                        //'updated_by',

                        [
                            'class' => 'common\grid\ActionColumn',
                            'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                            'template' => "{view}\n{delete}",
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
