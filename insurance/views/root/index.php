<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;

$this->context->layout = 'plain';

$this->title = Yii::t('general', 'Salamat: Coming soon');
?>

<div class="auth-container">
    <div class="auth-form">
        <div class="header">
            <div class="logo" style="height: 8rem;"><?php include Yii::getAlias('@common/web/img/logo.svg') ?></div>
            <h4 class="text-hint"><?= Yii::t('general', 'Coming soon') ?></h4>
        </div>
    </div>
</div>
