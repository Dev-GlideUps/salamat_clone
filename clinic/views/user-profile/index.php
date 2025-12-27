<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('user', 'User Profile');
$this->params['breadcrumbs'][] = $this->title;

$profileTime = Yii::t('general', 'Last updated {time}', [
    'time' => Yii::$app->formatter->asRelativeTime(Yii::$app->user->identity->updated_at),
]);
$passwordTime = Yii::$app->user->identity->password_updated_at == null ? '' : Yii::t('general', 'Last updated {time}', [
    'time' => Yii::$app->formatter->asRelativeTime(Yii::$app->user->identity->password_updated_at),
]);
?>

<div class="container-custom">
    <div class="row">
        <div class="col pt-4">
            <h5 class="text-center mb-4"><?= Yii::t('user', 'Manage your info, privacy, and security to make Salamat work better for you') ?></h5>
        </div>
    </div>
    <div class="row align-items-center justify-content-center">
        <div class="col-lg-5 col-md-6">
            <a href="<?= Url::to(['update']) ?>" class="card raised-card action-card mb-3">
                <div class="card-header pt-3 pb-0"><h6 class="m-0"><?= Yii::t('user', 'Profile & personalization') ?></h6></div>
                <div class="row align-items-center no-gutters">
                    <div class="col">
                        <div class="card-body">
                            <p class="card-text text-secondary"><?= Yii::t('user', 'Profile information & account configuration') ?></p>
                            <p class="card-text"><small class="text-hint"><?= $profileTime ?></small></p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <?= Html::tag('div', file_get_contents(Yii::getAlias('@common/web/img/graphics/user-profile.svg')), [
                            'class' => 'card-img p-3',
                            'style' => 'width: 8rem; margin: 0 auto;',
                        ]) ?>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-5 col-md-6">
            <a href="<?= Url::to(['change-password']) ?>" class="card raised-card action-card mb-3">
                <div class="card-header pt-3 pb-0"><h6 class="m-0"><?= Yii::t('user', 'Security') ?></h6></div>
                <div class="row align-items-center no-gutters">
                    <div class="col">
                        <div class="card-body">
                            <p class="card-text text-secondary"><?= Yii::t('user', 'Account password and security settings') ?></p>
                            <p class="card-text"><small class="text-hint"><?= $passwordTime ?></small></p>
                        </div>
                    </div>
                    <div class="col-auto">
                        <?= Html::tag('div', file_get_contents(Yii::getAlias('@common/web/img/graphics/user-password.svg')), [
                            'class' => 'card-img p-3',
                            'style' => 'width: 8rem; margin: 0 auto;',
                        ]) ?>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>