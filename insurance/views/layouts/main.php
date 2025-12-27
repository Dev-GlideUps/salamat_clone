<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use clinic\assets\AppAsset;
use common\widgets\Snackbar;
use common\widgets\NavDrawer;
use common\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="ltr">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
$menuItems = [
    '<div class="mdc-list-container nav-links">',
    // [
    //     'label' => Yii::t('general', 'Dashboard'),
    //     'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/layers.svg')),
    //     'url' => ['/root/index'],
    // ],
    count(Yii::$app->user->identity->clinics) > 1 ?
    [
        'label' => Yii::t('clinic', 'Switch clinic'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/settings_1.svg')),
        'url' => ['/user/select-clinic'],
    ] : '',
    count(Yii::$app->user->identity->clinics) > 1 ?
    '<div class="mdc-divider"></div>' : '',
    '<div class="mdc-list-subtitle">'.Yii::t('clinic', 'Clinic / Hospital').'</div>',
    [
        'label' => Yii::t('clinic', 'Appointments'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')),
        'url' => ['/clinic/appointments'],
    ],
    // [
    //     'label' => Yii::t('clinic', 'Patients'),
    //     'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')),
    //     'url' => ['/root/patients'],
    // ],
    [
        'label' => Yii::t('clinic', 'Doctors'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')),
        'url' => ['/clinic/doctor'],
    ],
    [
        'label' => Yii::t('clinic', 'Branches'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/building.svg')),
        'url' => ['/clinic/branches'],
    ],
    [
        'label' => Yii::t('user', 'Users'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')),
        'url' => ['/clinic/users'],
    ],
    '</div>',
];
?>

<div id="mdc-nav-drawer">
    <div class="header">
        <div class="user-avatar">
            <span class="material-icon">person</span>
        </div>
        <div class="dropdown">
            <button class="mdc-list-item md-theme-dark user-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text">
                    <?= Yii::$app->user->identity->name ?>
                    <div class="secondary"><?= Yii::$app->user->identity->email ?></div>
                </div>
                <div class="meta icon">
                    <div class="material-icon">keyboard_arrow_down</div>
                </div>
            </button>
            <div class="dropdown-menu dropdown-menu-right bg-salamat-secondary">
                <div class="mdc-list-group">
                    <a class="mdc-list-item" href="javascript: ;">
                        <div class="text">Settings</div>
                        <div class="meta icon material-icon">settings</div>
                    </a>
                    <a class="mdc-list-item" href="<?= Url::to(['/user-profile/index']) ?>">
                        <div class="text">Profile</div>
                        <div class="meta icon material-icon">account_circle</div>
                    </a>
                    <?php
                    echo Html::beginForm(['/user/sign-out'], 'post');
                    echo Html::submitButton('<div class="text">'.Yii::t('user', 'Sign out').'</div><div class="icon material-icon meta">exit_to_app</div>', [
                        'class' => 'mdc-list-item',
                    ]);
                    echo Html::endForm();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mdc-list-item bg-salamat-color">
        <b class="text"><?= Yii::$app->user->identity->activeClinic->name ?></b>
    </div>
    
    <?php
    echo NavDrawer::widget([
        'customScroller' => true,
        'navItems' => $menuItems,
    ]);
    ?>
</div>
<div class="mdc-drawer-scrim"></div>
<div id="mdc-top-app-bar" class="md-theme-dark">
    <button class="material-icon nav-icon">menu</button>
    <?= Html::img(Yii::getAlias('@web/img/logo.png'), ['class' => 'salamat-logo']) ?>
</div>

<div class="container">
    <div class="row">
        <div class="col">
        <?php echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]); ?>
        </div>
    </div>
</div>

<?= $content ?>

<div class="p-5"></div>

<?= Snackbar::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
