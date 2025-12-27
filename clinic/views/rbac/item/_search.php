<?php

use yii\helpers\ArrayHelper;
use vip9008\MDC\helpers\Html;
use vip9008\MDC\widgets\ActiveForm;
use vip9008\MDC\assets\CardAsset;

use backend\models\organization\Plan;

CardAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\PlanFeatureSearch */
/* @var $form yii\widgets\ActiveForm */

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');

$plans = Plan::find()->select('name')->indexBy('id')->column();
?>

<?php $form = ActiveForm::begin([
    'themeColor' => $accentColor,
    'fieldConfig' => [
        'options' => ['class' => 'full-width'],
    ],
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="mdc-card plan-feature-search">
    <div class="mdc-card-primary">
        <div class="row">
            <!-- <div class="col">
                <?= '' // $form->field($model, 'id') ?>
            </div> -->
            <div class="col large-6 medium-6">
                <?= $form->field($model, 'plan_id')->dropDownList($plans, [
                    'class' => 'mdc-searchable full-width',
                    'errorMessage' => Yii::t('general', "Can't find any match!"),
                ]) ?>
            </div>
            <!-- <div class="col">
                <?= '' // $form->field($model, 'slug') ?>
            </div> -->
            <div class="col large-6 medium-6">
                <?= $form->field($model, 'name') ?>
            </div>
            <div class="col">
                <?= '' // $form->field($model, 'description') ?>
            </div>
            <div class="col">
                <?= '' // $form->field($model, 'value') ?>
            </div>
            <div class="col">
                <?= '' // $form->field($model, 'resettable_period') ?>
            </div>
            <div class="col">
                <?= '' // $form->field($model, 'resettable_interval') ?>
            </div>
        </div>
    </div>

    <div class="mdc-button-group direction-reverse">
        <?= Html::submitButton(Yii::t('plans', 'Search'), ['class' => "mdc-button btn-contained bg-$primaryColor"]) ?>
        <?= Html::a(Yii::t('plans', 'Reset'), ['index'], ['class' => "mdc-button btn-outlined bg-$accentColor"]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
