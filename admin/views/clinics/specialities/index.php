<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\SpecialitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Specialities');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="mdc-button-group direction-reverse mx-1">
                    <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('clinic', 'New speciality'), ['create'], ['class' => 'mdc-button salamat-color']) ?>
                </div>
                <div class="mdc-divider"></div>

                <?= $this->render('_search', [
                    'model' => $searchModel,
                ]) ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'title',
                        'title_ar',
                        'created_at:datetime',
                        // 'updated_at:datetime',

                        ['class' => 'common\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
