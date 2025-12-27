<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Users');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'common\grid\SerialColumn'],
    
                    // 'id',
                    [
                        'attribute' => 'name',
                        'value' => function ($model) {
                            return Html::beginTag('div', ['class' => 'mdc-list-item']).
                                Html::tag('div', 'person', ['class' => 'graphic material-icon bg-salamat-color']).
                                Html::beginTag('div', ['class' => 'text']).
                                    $model->name.
                                    Html::tag('div', $model->email, ['class' => 'secondary']).
                                Html::endTag('div').
                            Html::endTag('div');
                        },
                        'contentOptions' => ['class' => 'p-0'],
                        'format' => 'raw',
                    ],
                    'phone',
                    // 'password_hash',
                    // 'access_token',
                    // 'auth_key',
                    // 'active_clinic',
                    // 'registration_ip',
                    // 'password_updated_at:datetime',
                    'last_login_at:datetime',
                    [
                        'label' => Yii::t('user', 'Confirmed'),
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function ($model) {
                            if ($model->isConfirmed) {
                                return Html::tag('div', 'check', ['class' => 'material-icon']);
                            } else {
                                return Html::tag('div', 'close', ['class' => 'material-icon']);
                            }
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => Yii::t('user', 'Blocked'),
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function ($model) {
                            $link = $model->clinicLinks[0];
                            foreach($model->clinicLinks as $item) {
                                if ($item->clinic_id == Yii::$app->user->identity->active_clinic) {
                                    $link = $item;
                                    break;
                                }
                            }

                            if ($link->isBlocked) {
                                return Html::tag('div', 'block', ['class' => 'material-icon']);
                            } else {
                                return '';
                            }
                        },
                        'format' => 'html',
                    ],
                    // 'created_at',
                    // 'updated_at',
    
                    [
                        'class' => 'common\grid\ActionColumn',
                        'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                        'template' => "{view}\n{update-blocked-state}\n{update-permissions}\n",
                        'buttons' => [
                            'update-blocked-state' => function ($url, $model, $key) {
                                if (!Yii::$app->user->can('Control users')) {
                                    return '';
                                }

                                $link = $model->clinicLinks[0];
                                foreach($model->clinicLinks as $item) {
                                    if ($item->clinic_id == Yii::$app->user->identity->active_clinic) {
                                        $link = $item;
                                        break;
                                    }
                                }

                                $action = Yii::t('user', 'Block user');
                                $dialog = '#user-account-block';

                                if ($link->isBlocked) {
                                    $action = Yii::t('user', 'Unblock user');
                                    $dialog = '#user-account-unblock';
                                }

                                return Html::a(Html::tag('div', 'block', ['class'=> 'icon material-icon']).Html::tag('div', $action, ['class' => 'text']), $dialog, [
                                    'class' => 'mdc-list-item salamat-color',
                                    'onclick' => "setBlockDialogAction('$dialog', '$url');",
                                    'data' => [
                                        'toggle' => 'modal',
                                    ],
                                ]);
                            },
                            'update-permissions' => function ($url, $model, $key) {
                                if (!Yii::$app->user->can('Control users')) {
                                    return '';
                                }

                                return Html::a(Html::tag('div', 'security', ['class'=> 'icon material-icon']).Html::tag('div', Yii::t('user', 'Access control'), ['class' => 'text']), $url, [
                                    'class' => 'mdc-list-item salamat-color',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>

<?php if (Yii::$app->user->can('Control users')) { ?>
<div class="modal fade" id="user-account-block" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('user', 'Block user account') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('user', 'User account will be blocked. the account will lose all privileges and will not be able to sign-in anymore.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" onclick="resetBlockDialogAction();" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a(Yii::t('user', 'Block user'), 'javascript: ;', ['class' => 'mdc-button salamat-color action-button', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user-account-unblock" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('user', 'Unblock user account') ?></div>
            </div>
            <div class="modal-body"><?= Yii::t('user', 'User account will be unblocked. the account will re-gain all privileges and will be able to sign-in again.') ?></div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" onclick="resetBlockDialogAction();" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::a(Yii::t('user', 'Unblock user'), 'javascript: ;', ['class' => 'mdc-button salamat-color action-button', 'data-method' => 'post']) ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
