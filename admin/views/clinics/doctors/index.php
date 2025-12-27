<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\DoctorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Doctors');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="mdc-button-group direction-reverse mx-1">
                    <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('clinic', 'New doctor'), ['create'], ['class' => 'mdc-button salamat-color']) ?>
                </div>
                <div class="mdc-divider"></div>

                <?= $this->render('_search', [
                    'model' => $searchModel,
                    'specialities' => $specialities,
                ]) ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'label' => Yii::t('clinic', 'Doctor'),
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
                        // 'name',
                        // 'name_alt',
                        'mobile',
                        [
                            'attribute' => 'speciality',
                            'value' => function ($model) {
                                return @$model->specialization->title;
                            },
                        ],
                        // 'description:ntext',
                        'experience:date',
                        //'language',
                        //'photo',
                        //'user_id',
                        // 'updated_at:datetime',
                        // 'created_at:datetime',

                        ['class' => 'common\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>