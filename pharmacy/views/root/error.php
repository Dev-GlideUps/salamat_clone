<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use Yii;
use yii\helpers\Html;

$this->title = $name;
?>

<div class="container site-error card raised-card">
    <div class="row justify-content-center align-items-center py-5">
        <div class="col-lg-4 col-md-5">
            <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/page-error-1.svg')) ?>
        </div>
        <div class="col-auto text-center">
            <h1 class="text-hint"><?= $exception->statusCode ?></h1>
            <h4 class="text-secondary"><?= Yii::t('general', 'Oops! Something went wrong.') ?></h4>
            <h6 class="text-hint"><?= nl2br(Html::encode($message)) ?></h6>

            <div class="mdc-button-group direction-stack">
                <?= Html::a(Yii::t('general', 'Go back to home'), ['/root/index'], ['class' => 'mdc-button btn-outlined salamat-color']) ?>
            </div>
        </div>
    </div>
</div>
