<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use admin\assets\AppAsset;
use common\widgets\Snackbar;
use common\widgets\NavDrawer;
use admin\widgets\Breadcrumbs;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="ltr">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="57x57" href="<?= Yii::getAlias('@web/app_icon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= Yii::getAlias('@web/app_icon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= Yii::getAlias('@web/app_icon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= Yii::getAlias('@web/app_icon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= Yii::getAlias('@web/app_icon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= Yii::getAlias('@web/app_icon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= Yii::getAlias('@web/app_icon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= Yii::getAlias('@web/app_icon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web/app_icon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?= Yii::getAlias('@web/app_icon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web/app_icon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= Yii::getAlias('@web/app_icon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Yii::getAlias('@web/app_icon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= Yii::getAlias('@web/app_icon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#212121">
    <meta name="msapplication-TileImage" content="<?= Yii::getAlias('@web/app_icon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#212121">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="">
<?php $this->beginBody() ?>

<?php
$menuItems = [
    '<div class="mdc-divider"></div>',
    '<div class="mdc-list-container nav-links">',
    [
        'label' => Yii::t('general', 'Dashboard'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/layers.svg')),
        'url' => ['/root/index'],
    ],
    '<div class="mdc-divider"></div>',
    '<div class="mdc-list-subtitle">'.Yii::t('clinic', 'Clinics data').'</div>',
    [
        'label' => Yii::t('clinic', 'Clinics / Hospitals'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/building.svg')),
        'url' => ['/clinics/index'],
    ],
    [
        'label' => Yii::t('clinic', 'Branches'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/door_open.svg')),
        'url' => ['/clinics/branches/index'],
    ],
    [
        'label' => Yii::t('clinic', 'Doctors'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_user.svg')),
        'url' => ['/clinics/doctors/index'],
    ],
    [
        'label' => Yii::t('user', 'Users'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')),
        'url' => ['/clinics/users/index'],
    ],
    '<div class="mdc-divider"></div>',
    [
        'label' => Yii::t('clinic', 'Specialities'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
        'url' => ['/clinics/specialities/index'],
    ],
    [
        'label' => Yii::t('general', 'ICD-10-CM'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')),
        'url' => ['/clinics/icd10-cm/index'],
    ],
    '<div class="mdc-divider"></div>',
    [
        'label' => Yii::t('clinic', 'Consent Form'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
        'url' => ['/consent-form/index'],
    ],
    [
        'label' => Yii::t('general', 'Add Consent Form'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')),
        'url' => ['/consent-form/create'],
    ],
    '<div class="mdc-divider"></div>',
    '<div class="mdc-list-subtitle">'.Yii::t('patient', 'Patients data').'</div>',
    [
        'label' => Yii::t('patient', 'Patients'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')),
        'url' => ['/patients/index'],
    ],
    [
        'label' => Yii::t('patient', 'Sick leaves'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')),
        'url' => ['/patients/sick-leaves/index'],
    ],
    [
        'label' => Yii::t('general', 'Categories'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/attachment2.svg')),
        'url' => ['/patients/attachment-categories/index'],
    ],
    '<div class="mdc-divider"></div>',
    '<div class="mdc-list-subtitle">'.Yii::t('dental', 'Dental data').'</div>',
    [
        'label' => Yii::t('general', 'Categories'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')),
        'url' => ['/dental/categories/index'],
    ],
    [
        'label' => Yii::t('clinic', 'Procedures'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')),
        'url' => ['/dental/procedures/index'],
    ],
    '<div class="mdc-divider"></div>',
    '<div class="mdc-list-subtitle">'.Yii::t('insurance', 'Insurance data').'</div>',
    [
        'label' => Yii::t('insurance', 'Companies'),
        'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_thunder.svg')),
        'url' => ['/insurance/companies/index'],
    ],
    '<div class="mdc-divider"></div>',
        '<div class="mdc-list-subtitle">' . Yii::t('Sms', 'Sms') . '</div>',
        [
            'label' => Yii::t('Sms', 'Sms Log'),
            'support' => file_get_contents(Yii::getAlias('@common/web/img/svg_icons/shield_thunder.svg')),
            'url' => ['/root/sms-log'],
        ],
];
?>

<div id="mdc-nav-drawer" class="bg-salamat-secondary">
    <div class="header">
        <a class="barnd-logo" href="<?= Yii::$app->homeUrl ?>">
            <img src="<?= Yii::getAlias('@web/img/logo.png') ?>">
            <sub>ADMIN</sub>
        </a>
    </div>
    
    <?php
    echo NavDrawer::widget([
        'customScroller' => true,
        'navItems' => $menuItems,
    ]);
    ?>
</div>
<div class="mdc-drawer-scrim"></div>
<div id="mdc-top-app-bar">
    <button class="material-icon nav-icon text-secondary">menu</button>
    <?php echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>

    <div class="actions">
        <div class="action-item">
            <div class="dropdown" style="height: 1.5rem;">
                <button class="material-icon dropdown-toggle hide-caret text-secondary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">account_circle</button>
                <div class="dropdown-menu dropdown-menu-right bg-salamat-secondary" style="width: 18rem;">
                    <div class="mdc-list-group">
                        <div class="mdc-list-item">
                            <div class="graphic material-icon bg-salamat-color">person</div>
                            <div class="text">
                                <?= Yii::$app->user->identity->name ?>
                                <div class="secondary"><?= Yii::$app->user->identity->email ?></div>
                            </div>
                        </div>
                        <div class="mdc-divider my-2"></div>
                        <?php
                        echo Html::beginForm(['/root/sign-out'], 'post');
                        echo Html::submitButton('<div class="text">'.Yii::t('user', 'Sign out').'</div><div class="icon material-icon meta">exit_to_app</div>', [
                            'class' => 'mdc-list-item',
                        ]);
                        echo Html::endForm();
                        ?>
                    </div>
                </div>
            </div>
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
