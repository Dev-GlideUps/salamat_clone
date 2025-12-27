<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = Yii::t('user', 'Access control');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->name, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <h4><?= Html::encode($this->title) ?></h4>

            <?php $form = ActiveForm::begin(['id' => 'permissions-form']); ?>
            <div class="card raised-card">
                <div class="mdc-list-item">
                    <div class="text">
                        <?= $user->name ?>
                        <div class="secondary"><?= $user->email ?></div>
                    </div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <p class="mdt-body text-secondary"><?= Yii::t('user', 'Roles (system default)') ?></p>
                    <div class="row">
                    <?php foreach ($items['roles'] as $item => $info) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <?= $form->field($model, "items[$item]")->checkbox()->label($item) ?>
                        </div>
                    <?php } ?>
                    </div>

                    <p class="mdt-body text-secondary mt-3"><?= Yii::t('user', 'Permissions') ?></p>
                    <div class="row">
                    <?php $group = explode(' ', array_key_first($items['permissions']))[1]; ?>
                    <?php foreach ($items['permissions'] as $item => $info) { ?>
                        <?php
                            $itemGroup = explode(' ', $item)[1];
                            if ($itemGroup != $group) {
                                echo "</div><div class=\"mdc-divider mb-3\"></div><div class=\"row\">";
                                $group = $itemGroup;
                            }
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <?= $form->field($model, "items[$item]")->checkbox()->label($item)->hint($info->description) ?>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="mdc-button-group direction-reverse py-0">
                        <?= Html::submitButton(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), [
                            'class' => 'mdc-button salamat-color',
                        ]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
