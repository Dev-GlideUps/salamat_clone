<?php

use vip9008\MDC\assets\CardAsset;
use yii\helpers\ArrayHelper;
use vip9008\MDC\helpers\Html;
use vip9008\MDC\widgets\ActiveForm;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */
/* @var $form ActiveForm */

CardAsset::register($this);

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>

<div class="auth-item-form">
    <?php $form = ActiveForm::begin([
        'themeColor' => $accentColor,
        'fieldConfig' => [
            'options' => ['class' => 'full-width'],
        ],
    ]); ?>
    <div class="mdc-card">
        <div class="mdc-card-primary">

            <div class="header <?= $primaryColor ?>">
                <div class="title">
                    <?php if ($model->isNewRecord) { ?>
                        <h5 class="text-secondary"><?= Yii::t('rbac-admin', 'New ' . $this->title) ?></h5>
                    <?php } else { ?>
                        <h5 class="text-secondary"><?= "{$model->name}" ?></h5>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col large-6 medium-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
                </div>
                <div class="col large-6 medium-6">
                    <?= $form->field($model, 'className')->textInput() ?>
                </div>
            </div>
        </div>

        <div class="mdc-button-group direction-reverse">
            <?= Html::submitButton(Yii::t('rbac-admin', 'Save'), ['class' => "mdc-button btn-contained bg-$primaryColor", 'name' => 'submit-button']) ?>
            <?= Html::a(Yii::t('rbac-admin', 'Cancel'), ['index'], ['class' => "mdc-button btn-outlined bg-$accentColor"]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
