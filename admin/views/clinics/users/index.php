<?php

use yii\helpers\Html;
use common\grid\GridView;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Users');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinics Data');
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
        </div>

        <?= $this->render('_search', [
            'model' => $searchModel,
            'clinics' => $clinics,
        ]) ?>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <div class="mdc-button-group direction-reverse mx-1">
                    <?= Html::a(Html::tag('div', 'add', ['class' => 'material-icon icon']).Yii::t('user', 'New User'), ['register'], ['class' => 'mdc-button salamat-color']) ?>
                    <?= Html::a(Html::tag('div', 'link', ['class' => 'material-icon icon']).Yii::t('clinic', 'Link Clinic'), ['link-clinic'], ['class' => 'mdc-button salamat-color']) ?>
                </div>
                <div class="mdc-divider"></div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        // ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'email:email',
                        'name',
                        // 'phone',
                        // 'password_hash',
                        // 'access_token',
                        // 'auth_key',
                        // 'active_clinic',
                        'registration_ip',
                        // 'password_updated_at:datetime',
                        // 'last_login_at:datetime',
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
                        'created_at:datetime',
                        // 'updated_at',

                        [
                            'class' => 'common\grid\ActionColumn',
                            'type' => \common\grid\ActionColumn::TYPE_DROPDOWN,
                            'template' => "{view}\n{change-password}\n{update}\n{delete}",
                            'buttons' => [
                                'change-password' => function ($url, $model, $key) {
                                    return Html::a(Html::tag('div', 'security', ['class'=> 'icon material-icon']).Html::tag('div', Yii::t('user', 'Change password'), ['class'=> 'text']), '#user-update-password', [
                                        'class' => 'mdc-list-item salamat-color',
                                        'onclick' => "setPasswordForm($model->id);",
                                        'data' => [
                                            'toggle' => 'modal',
                                            // 'target' => '#user-update-password',
                                        ],
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

<div class="modal fade" id="user-update-password" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['change-password'],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('user', 'Change password') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($passwordForm, 'userID')->hiddenInput() ?>
                <?= $form->field($passwordForm, 'new_password')->passwordInput(['autocomplete' => 'off']) ?>
                <?= $form->field($passwordForm, 'confirm_password')->passwordInput(['autocomplete' => 'off']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('user', 'Change'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>