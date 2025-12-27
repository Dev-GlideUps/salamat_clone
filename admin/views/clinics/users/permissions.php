<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = Yii::t('user', 'Access control');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $link->user->username, 'url' => ['view', 'id' => $link->user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <?php $form = ActiveForm::begin(['id' => 'permissions-form']); ?>
            <div class="card raised-card">
                <div class="mdc-list-item">
                    <div class="graphic icon bg-salamat-color"><div class="material-icon">person</div></div>
                    <div class="text">
                        <?= $link->user->name ?>
                        <div class="secondary"><?= $link->user->email ?></div>
                    </div>
                </div>
                <div class="mdc-list-item">
                    <div class="text">
                        <?= $link->clinic->name ?>
                        <div class="secondary"><?= $link->clinic->phone ?></div>
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
