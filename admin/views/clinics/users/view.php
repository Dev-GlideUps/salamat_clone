<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function() {
    $('#user-clinic-tabs .nav-item .nav-link').first().trigger('click');
});
JS;

$this->registerJs($script, $this::POS_END);

$formatter = Yii::$app->formatter;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'email:email',
                            'name',
                            'phone',
                            'access_token',
                            'auth_key',
                            [
                                'attribute' => 'active_clinic',
                                'value' => function($model) {
                                    if (!$model->activeClinic) {
                                        return null;
                                    }
            
                                    return Html::a($model->activeClinic->name, ['/clinics/view', 'id' => $model->active_clinic]);
                                },
                                'format' => 'raw',
                            ],
                            'registration_ip',
                            'password_updated_at:datetime',
                            'last_login_at:datetime',
                            'confirmed_at:datetime',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        <?= Html::button(Html::tag('div', 'delete', ['class' => 'icon material-icon']).Yii::t('general', 'Delete'), [
                            'class' => 'mdc-button btn-outlined salamat-color',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#delete-record',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <h5 class="mt-4"><?= Yii::t('clinic', 'Clinics') ?></h5>
            <div class="card raised-card mt-4">
            <?php if (empty($relations)) { ?>
                <div class="card-body">
                    <div class="py-5 text-center">
                        <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                            <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                            <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No clinics!') ?></h5>
                            <?= Html::a(Yii::t('clinic', 'Link to clinic'), ['link-clinic'], ['class' => 'mdc-button btn-outlined salamat-color']); ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="mdc-button-group direction-reverse mx-1">
                    <?= Html::a(Yii::t('clinic', 'Link to clinic'), ['link-clinic'], ['class' => 'mdc-button btn-outlined salamat-color']); ?>
                </div>
                <ul class="nav nav-tabs" id="user-clinic-tabs" role="tablist">
                <?php foreach ($relations as $clinicId => $item) { ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#clinic-<?= $clinicId ?>" role="tab" aria-selected="false"><?= $item->clinic->name ?></a>
                    </li>
                <?php } ?>
                </ul>
                <div class="tab-content">
                <?php foreach ($relations as $clinicId => $item) { ?>
                    <?php
                    $action = Yii::t('user', 'Block user');
                    $actionTitle = Yii::t('user', 'Block user account');
                    $actionDescription = Yii::t('user', 'User account will be blocked. the account will lose all privileges and will not be able to sign-in anymore.');
                    if ($item->link->isBlocked) {
                        $action = Yii::t('user', 'Unblock user');
                        $actionTitle = Yii::t('user', 'Unblock user account');
                        $actionDescription = Yii::t('user', 'User account will be unblocked. the account will re-gain all privileges and will be able to sign-in again.');
                    }
                    ?>
                    <div class="tab-pane fade" id="clinic-<?= $clinicId ?>" role="tabpanel">
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-secondary" style="width: 25%;"><?= $item->clinic->getAttributeLabel('id') ?></td>
                                        <td><?= $clinicId ?></td>
                                    <tr>
                                    </tr>
                                        <td class="text-secondary" style="width: 25%;"><?= $item->clinic->getAttributeLabel('name') ?></td>
                                        <td><?= Html::a($item->clinic->name, ['/clinics/view', 'id' => $clinicId]) ?></td>
                                    </tr>
                                    </tr>
                                        <td class="text-secondary" style="width: 25%;"><?= $item->clinic->getAttributeLabel('phone') ?></td>
                                        <td><?= $item->clinic->phone ?></td>
                                    </tr>
                                    </tr>
                                        <td class="text-secondary" style="width: 25%;"><?= $item->link->getAttributeLabel('blocked_at') ?></td>
                                        <td><?= $formatter->asDateTime($item->link->blocked_at) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php
                            $permissions = $item->assignment->getItems(true);
                            $assignedPermissions = $item->assignment->getAssignments();
                            ?>
                            <div class="mdc-divider"></div>
                            <h6 class="text-secondary mt-4 mb-4"><?= Yii::t('user', 'Authorized access') ?></h6>
                            <div class="mdt-body text-secondary mt-3"><?= Yii::t('user', 'Roles (system default)') ?></div>
                            <div class="row">
                            <?php foreach ($permissions['roles'] as $permission => $info) { ?>
                                <?php $isAssigned = in_array($permission, $assignedPermissions); ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="rbac-item mdc-list-item <?= $isAssigned ? 'salamat-color' : '' ?>"">
                                        <div class="icon material-icon ml-0 text-secondary"><?= $isAssigned ? 'check_box' : 'check_box_outline_blank' ?></div>
                                        <div class="text mr-0" style="white-space: normal;"><?= $permission ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>

                            <div class="mdt-body text-secondary mt-3"><?= Yii::t('user', 'Permissions') ?></div>
                            <div class="row">
                            <?php $group = explode(' ', array_key_first($permissions['permissions']))[1]; ?>
                            <?php foreach ($permissions['permissions'] as $permission => $info) { ?>
                                <?php
                                    $isAssigned = in_array($permission, $assignedPermissions);
                                    $itemGroup = explode(' ', $permission)[1];
                                    if ($itemGroup != $group) {
                                        echo "</div><div class=\"mdc-divider\"></div><div class=\"row\">";
                                        $group = $itemGroup;
                                    }
                                ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="rbac-item mdc-list-item md-3line <?= $isAssigned ? 'salamat-color' : '' ?>"">
                                        <div class="icon material-icon ml-0 text-secondary"><?= $isAssigned ? 'check_box' : 'check_box_outline_blank' ?></div>
                                        <div class="text mr-0" style="white-space: normal;">
                                            <?= $permission ?>
                                            <div class="secondary"><?= $info->description ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                            
                            <div class="mdc-button-group direction-reverse py-0">
                                <?= Html::a(Html::tag('div', 'security', ['class' => 'icon material-icon d-none']).Yii::t('user', 'Access control'), ['update-permissions', 'user_id' => $model->id, 'clinic_id' => $clinicId], [
                                    'class' => 'mdc-button salamat-color',
                                ]) ?>
                                <?= Html::button(Html::tag('div', 'block', ['class' => 'icon material-icon d-none']).$action, [
                                    'class' => 'mdc-button salamat-color',
                                    'data' => [
                                        'toggle' => 'modal',
                                        'target' => "#blocked-state-$clinicId",
                                    ],
                                ]) ?>
                            </div>

                        </div>
                    </div>

                    <div class="modal fade" id="blocked-state-<?= $clinicId ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-title"><?= $actionTitle ?></div>
                                </div>
                                <div class="modal-body"><?= $actionDescription ?></div>
                                <div class="modal-footer">
                                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                                    <?= Html::a($action, ['update-blocked-state', 'user_id' => $model->id, 'clinic_id' => $clinicId], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                </div>
            <?php } ?>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="delete-record" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('user', 'Delete user') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('general', 'The record will be deleted from the database. this action cannot be undone.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a(Yii::t('user', 'Delete user'), ['delete', 'id' => $model->id], ['class' => 'mdc-button salamat-color', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>
