<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use common\widgets\ActiveForm;

$this->context->layout = 'plain';

$this->title = Yii::t('clinic', 'Select clinic');
?>

<div class="auth-container">
    <div class="pexels">Photo by <a href="https://www.pexels.com/@suzyhazelwood" target="_blank">Suzy Hazelwood</a></div>

    <?php $form = ActiveForm::begin([
        'id' => 'select-clinic-form',
        'options' => ['class' => 'auth-form'],
    ]); ?>
        <div class="header">
            <div class="logo"><?php include Yii::getAlias('@common/web/img/logo.svg') ?></div>
            <h5><?= Yii::t('clinic', 'Select clinic') ?></h5>
            <div class="selected-user disabled">
                <div class="material-icon leading">person</div>
                <div class="text"><?= Yii::$app->user->identity->email ?></div>
            </div>
        </div>

        <div class="mdc-list-container clinics-list">
            <div class="mdc-list-group">
                <?php foreach ($clinics as $i => $item) { ?>
                <?php if ($i > 0) { ?>
                <div class="mdc-divider m-0"></div>
                <?php } ?>
                <button class="mdc-list-item salamat-color" type="submit" name="ClinicSelectForm[clinic_id]" value="<?= $item->id ?>">
                    <div class="text"><?= $item->name ?></div>
                    <div class="meta icon">
                        <div class="material-icon">chevron_right</div>
                    </div>
                </button>
                <?php } ?>
            </div>
        </div>

        <div class="loading-block">
            <div class="mdc-progress-track salamat-color">
                <div class="indicator indeterminate"></div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
