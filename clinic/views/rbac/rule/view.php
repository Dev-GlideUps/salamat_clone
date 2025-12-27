<?php

use vip9008\MDC\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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
                        'className',
                    ],
                    'actions' => [
                        Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], [
                            'class' => "mdc-button btn-contained bg-$primaryColor",
                        ]),
                        Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
                            'class' => "mdc-button btn-contained bg-red",
                            'data' => [
                                'confirm' => Yii::t('plans', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                                'data-method' => 'post',
                            ],
                        ]),

                        'options' => ['class' => 'direction-reverse'],
                    ],
                ]);
                ?>

            </div>

        </div>
    </div>
</section>