<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\hr\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('hr', 'Employees');
$this->params['breadcrumbs'][] = Yii::t('hr', 'Human resources');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')) ?>
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
                        'attribute' => 'name',
                        'label' => Yii::t('hr', 'Employee'),
                        'value' => function ($model) {
                            return Html::tag('div',
                                Html::tag('div', Html::tag('div', 'person', ['class' => 'material-icon']), ['class' => 'graphic bg-salamat-color m-0']).
                                Html::tag('div',
                                    $model->name.
                                    Html::tag('div', $model->cpr, ['class' => 'secondary']),
                                ['class' => 'text my-0 mr-0']),
                            ['class'=> 'mdc-list-item']);
                        },
                        'format' => 'html',
                    ],
                    'phone',
                    'contract_start:date',
                    'created_at:datetime',
                    'updated_at:datetime',
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
        Yii::t('hr', 'New employee'), ['create'], [
        'class' => 'mdc-fab-button extended bg-salamat-color',
    ]) ?>
</div>