<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac', 'Assignments');
$this->params['breadcrumbs'][] = Yii::t('rbac', 'Access control');
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

        <?php //= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card diagnosis-index">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => Yii::t('user', 'User'),
                        'value' => function ($model) {
                            return Html::beginTag('div', ['class' => 'mdc-list-item']).
                                Html::tag('div', 'person', ['class' => 'graphic material-icon bg-salamat-color']).
                                Html::beginTag('div', ['class' => 'text']).
                                    $model->user->name.
                                    Html::tag('div', $model->user->email, ['class' => 'secondary']).
                                Html::endTag('div').
                            Html::endTag('div');
                        },
                        'contentOptions' => ['class' => 'p-0'],
                        'format' => 'raw',
                    ],
                    'user.phone',
                    'user.last_login_at:datetime',

                    [
                        'class' => 'common\grid\ActionColumn',
                        'template' => "{view}",
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                $url = ['view', 'id' => $model->user_id];
                                $options = [
                                    'title' => Yii::t('general', 'Display detials'),
                                    'aria-label' => Yii::t('general', 'Display detials'),
                                    'class' => 'material-icon mx-2',
                                ];
                                return Html::a("list_alt", $url, $options);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>
