<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use vip9008\MDC\components\DataTable;
use yii\widgets\Pjax;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\BizRule */

$this->title = Yii::t('rbac-admin', 'Rules');
$this->params['breadcrumbs'][] = $this->title;

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>

<div class="container">
    <div class="row">
        <div class="col large-12">
            <section class="chapter organization-index">
                <div class="mdc-button-group">
                    <?= Html::a(Yii::t('rbac-admin', 'Create Rule'), ['create'], ['class' => "mdc-button btn-contained bg-$accentColor"]) ?>
                </div>

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?=
                DataTable::widget([
                    'dataProvider' => $dataProvider,
                    //        'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'label' => Yii::t('rbac-admin', 'Name'),
                        ],
                        ['class' => 'vip9008\MDC\widgets\ActionColumn',],
                    ],
                ]);
                ?>

            </section>
        </div>
    </div>
</div>