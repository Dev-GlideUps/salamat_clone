<?php

use yii\helpers\Html;
use common\grid\GridView;
/* @var $this yii\web\View */

$this->title = 'Sms Log';
$this->params['breadcrumbs'][] = Yii::t('Sms Log', 'Sms Log');
?>

<section class="container-custom">
    <!-- <h1>WELCOME</h1> -->
    <!-- <?= Html::a('Sms Log', ['/'], ['class' => 'mdc-button salamat-color']) ?> -->
</section>

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
            <div class="card raised-card pl-5">
                <div class="mdc-button-group direction-reverse mx-1"></div>
                <div class="mdc-divider"></div>
                <?= $this->render('_search', [
                    'model' => $model,
                    // 'specialities' => $specialities,
                ]) ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [

                        [
                            'label' => Yii::t('clinic', 'Name'),
                            'value' => function ($model) {
                                return $model->clinic->name;
                            },
                            'contentOptions' => ['class' => 'p-0'],
                            'format' => 'raw',
                        ],
                        'count',

                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>