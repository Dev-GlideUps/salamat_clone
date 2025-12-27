<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$action = Yii::t('user', 'Block user');
$actionTitle = Yii::t('user', 'Block user account');
$actionDescription = Yii::t('user', 'User account will be blocked. the account will lose all privileges and will not be able to sign-in anymore.');
if ($link->isBlocked) {
    $action = Yii::t('user', 'Unblock user');
    $actionTitle = Yii::t('user', 'Unblock user account');
    $actionDescription = Yii::t('user', 'User account will be unblocked. the account will re-gain all privileges and will be able to sign-in again.');
}

$items = $assignment->getItems(true);
$assigned = $assignment->getAssignments();
$children = $assignment->getItemsChildren($assigned);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <h4><?= Html::encode($this->title) ?></h4>

            <div class="card raised-card">
                <div class="card-body">
                    <h6 class="text-secondary"><?= Yii::t('user', 'User information') ?></h6>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            // 'id',
                            'email:email',
                            'name',
                            'phone',
                            // 'access_token',
                            // 'auth_key',
                            // 'registration_ip',
                            'password_updated_at:datetime',
                            'last_login_at:datetime',
                            'confirmed_at:datetime',
                            [
                                'label' => $link->getAttributeLabel('blocked_at'),
                                'value' => $link->blocked_at,
                                'format' => 'datetime',
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <h6 class="text-secondary mb-4"><?= Yii::t('user', 'Authorized access') ?></h6>
                    <div class="mdt-body text-secondary mt-3"><?= Yii::t('user', 'Roles (system default)') ?></div>
                    <div class="row">
                    <?php foreach ($items['roles'] as $item => $info) { ?>
                        <?php
                            $isAssigned = in_array($item, $assigned);
                            $isChild = isset($children[$item]);
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="rbac-item mdc-list-item <?= $isAssigned ? 'salamat-color' : '' ?>"">
                                <div class="icon material-icon ml-0 <?= $isChild ? 'text-hint' : 'text-secondary' ?>"><?= $isAssigned || $isChild ? 'check_box' : 'check_box_outline_blank' ?></div>
                                <div class="text mr-0" style="white-space: normal;"><?= $item ?></div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="mdt-body text-secondary mt-3"><?= Yii::t('user', 'Permissions') ?></div>
                    <div class="row">
                    <?php $group = explode(' ', array_key_first($items['permissions']))[1]; ?>
                    <?php foreach ($items['permissions'] as $item => $info) { ?>
                        <?php
                            $isAssigned = in_array($item, $assigned);
                            $isChild = isset($children[$item]);
                            $itemGroup = explode(' ', $item)[1];
                            if ($itemGroup != $group) {
                                echo "</div><div class=\"mdc-divider\"></div><div class=\"row\">";
                                $group = $itemGroup;
                            }
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="rbac-item mdc-list-item md-3line <?= $isAssigned ? 'salamat-color' : '' ?>"">
                                <div class="icon material-icon ml-0 <?= $isChild ? 'text-hint' : 'text-secondary' ?>"><?= $isAssigned || $isChild ? 'check_box' : 'check_box_outline_blank' ?></div>
                                <div class="text mr-0" style="white-space: normal;">
                                    <?= $item ?>
                                    <div class="secondary"><?= $info->description ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>

                    <?php if (Yii::$app->user->can('Control users')) { ?>
                    <div class="mdc-button-group direction-reverse py-0">
                        <?= Html::a(Html::tag('div', 'security', ['class' => 'icon material-icon d-none']).Yii::t('user', 'Access control'), ['update-permissions', 'id' => $model->id], [
                            'class' => 'mdc-button salamat-color',
                        ]) ?>
                        <?= Html::button(Html::tag('div', 'block', ['class' => 'icon material-icon d-none']).$action, [
                            'class' => 'mdc-button salamat-color',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#user-account-block',
                            ],
                        ]) ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (Yii::$app->user->can('Control users')) { ?>
<div class="modal fade" id="user-account-block" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= $actionTitle ?></div>
            </div>
            <div class="modal-body"><?= $actionDescription ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a($action, ['update-blocked-state', 'id' => $model->id], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
