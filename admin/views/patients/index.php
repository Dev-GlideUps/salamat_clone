<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\PatientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients data');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?>
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
                    <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('general', 'Create new'), ['create'], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                </div>
                <div class="mdc-divider"></div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'name',
                            'label' => Yii::t('general', 'Name'),
                            'value' => function ($model) {
                                return Html::beginTag('div', ['class' => 'mdc-list-item']).
                                    Html::tag('div', '', ['class' => 'graphic', 'style' => "background-image: url('$model->photoThumb');"]).
                                    Html::beginTag('div', ['class' => 'text']).
                                    $model->name.
                                    Html::tag('div', $model->name_alt, ['class' => 'secondary']).
                                    Html::endTag('div').
                                    Html::endTag('div');
                            },
                            'contentOptions' => ['class' => 'p-0'],
                            'format' => 'raw',
                        ],
                        'cpr',
                        'phone',
                        [
                            'attribute' => 'gender',
                            'value' => function ($model) {
                                return ($model->gender === null ? null : $model::genderList()[$model->gender]);
                            },
                        ],
                        [
                            'label' => Yii::t('general', 'Age'),
                            'value' => function ($model) {
                                return $model->age;
                            },
                        ],
                        //'dob',
                        //'height',
                        //'weight',
                        // 'created_at:datetime',
                        'updated_at:datetime',

                        [
                            'class' => 'common\grid\ActionColumn',
                            'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
