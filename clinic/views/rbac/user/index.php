<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use mdm\admin\components\Helper;
use vip9008\MDC\components\DataTable;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>
<div class="container">
    <div class="row">
        <div class="col large-12">
            <section class="chapter user-index">

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?=
                DataTable::widget([
                    'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
                    'columns' => [
//                        ['class' => 'vip9008\MDC\widgets\SerialColumn'],
                        'username',
                        'id',
                        'email:email',
//            [
//                'attribute' => 'status',
//                'value' => function($model) {
//                    return $model->isBlocked ? 'Blocked' : 'Active';
//                },
//                'filter' => [
//                    1 => 'Blocked',
//                    0 => 'Active'
//                ]
//            ],
//                        ['class' => 'vip9008\MDC\widgets\ActionColumn'],
                    ],
                ]);
                ?>

            </section>
        </div>
    </div>
</div>
