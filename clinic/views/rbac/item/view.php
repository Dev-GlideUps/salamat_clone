<?php

use mdm\admin\AnimateAsset;
use vip9008\MDC\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use vip9008\MDC\helpers\Html;
use vip9008\MDC\widgets\DetailView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\Plan */

$context = $this->context;
$labels = $context->labels();
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>
<section class="chapter item-view">
    <div class="container">
        <div class="row">
            <div class="col">
                <h4 class="<?= $primaryColor ?>"><?= Html::encode($this->title) ?></h4>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        'description:ntext',
                        'ruleName',
                        'data:ntext',
                    ],

                    'actions' => [
                        Html::a(Yii::t('general', 'Update'), ['update', 'id' => $model->name], [
                            'class' => "mdc-button btn-contained bg-$primaryColor",
                        ]),
                        Html::a(Yii::t('general', 'Delete'), ['delete', 'id' => $model->name], [
                            'class' => "mdc-button btn-contained bg-red",
                            'data' => [
                                'confirm' => Yii::t('general', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                                'data-method' => 'post',
                            ],
                        ]),

                        'options' => ['class' => 'direction-reverse'],
                    ],
                ]) ?>

            </div>

        </div>
    </div>
</section>
