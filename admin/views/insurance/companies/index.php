<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel insurance\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('isurance', 'Insurance companies');
$this->params['breadcrumbs'][] = Yii::t('isurance', 'Insurance Data');
$this->params['breadcrumbs'][] = Yii::t('isurance', 'Companies');
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_thunder.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
                        'name',
                        'name_alt',
                        'created_at:datetime',
                        'updated_at:datetime',

                        [
                            'class' => 'common\grid\ActionColumn',
                            'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                            'template' => "{view}\n{update}\n{delete}\n",
                            // 'buttons' => [
                            //     'update' => function ($url, $model, $key) {
                            //         $title = Yii::t('general', 'Update information');
                            //         $options = [
                            //             'title' => $title,
                            //             'aria-label' => $title,
                            //             'class' => 'mdc-list-item salamat-color',
                            //             'onclick' => "setICD10UpdateForm($model->id, '$model->code', '$model->description');",
                            //             'data-toggle' => 'modal',
                            //         ];
                            //         return Html::a(Html::tag('div', 'edit', ['class' => 'icon material-icon']).Html::tag('div', $title, ['class' => 'text']), "#update-code", $options);
                            //     },
                            // ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
