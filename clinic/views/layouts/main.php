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
    <meta name="msapplication-TileColor" content="#9E9D24">
    <meta name="msapplication-TileImage" content="<?= Yii::getAlias('@web/app_icon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#9E9D24">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= "Salamat - ".Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<?php
$user = Yii::$app->user;
$menuItems = include Yii::getAlias('@clinic/config/menu-items.php');
$darkTheme = $user->identity->dark_theme;
$theme = $darkTheme ? 'md-theme-dark' : 'md-theme-light';
$ajaxUrl = Url::to(['/user-profile/change-theme']);
$csrfToekn = Yii::$app->request->getCsrfToken();
$script = <<< JS
$('#dark-theme-switch').on('change', function () {
    $('#dark-theme-switch').closest('.has-loading').children('.loading-block').addClass('active');

    $.ajax({
        url: '$ajaxUrl',
        type: 'post',
        data: {
            darkTheme: $('#dark-theme-switch').prop('checked') ? 1 : 0,
            _csrf: '$csrfToekn'
        },
        success: function (data) {
            if (data == 1) {
                $('body').addClass('md-theme-dark').removeClass('md-theme-light');
                $('#day-night-art').addClass('switch');
            } else {
                $('body').addClass('md-theme-light').removeClass('md-theme-dark');
                $('#day-night-art').removeClass('switch');
            }

            $('#dark-theme-switch').closest('.has-loading').children('.loading-block').removeClass('active');
        },
        error: function (data) {
            var switchValue = !$('#dark-theme-switch').prop('checked');
            $('#dark-theme-switch').prop('checked', switchValue);
            $('#dark-theme-switch').closest('.has-loading').children('.loading-block').removeClass('active');
        }
    });
});
JS;

$this->registerJs($script, $this::POS_END);
?>

<body class="mdc-top-app-bar-visible <?= $theme ?>">
<?php $this->beginBody() ?>

<div id="mdc-nav-drawer">
    <div class="header">
        <div class="user-avatar">
            <span class="material-icon">person</span>
        </div>
        <div class="dropdown">
            <button class="mdc-list-item md-theme-dark user-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text">
                    <?= $user->identity->name ?>
                    <div class="secondary"><?= $user->identity->email ?></div>
                </div>
                <div class="meta icon">
                    <div class="material-icon">keyboard_arrow_down</div>
                </div>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="mdc-list-group">
                    <a class="mdc-list-item d-none" href="javascript: ;">
                        <div class="text"><?= Yii::t('general', 'Settings') ?></div>
                        <div class="meta icon material-icon">settings</div>
                    </a>
                    <a class="mdc-list-item" href="<?= Url::to(['/user-profile']) ?>">
                        <div class="text"><?= Yii::t('user', 'Profile') ?></div>
                        <div class="meta icon material-icon">account_circle</div>
                    </a>
                    <button class="mdc-list-item" data-toggle="modal" data-target="#dark-mode-modal">
                        <div class="text"><?= Yii::t('general', 'Dark theme') ?></div>
                        <div class="meta icon material-icon">brightness_4</div>
                    </button>
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

        <div class="mdc-list-item bg-salamat-color">
            <b class="text"><?= $user->identity->activeClinic->name ?></b>
        </div>
    </div>
    
    <?php
    echo NavDrawer::widget([
        'customScroller' => true,
        'navItems' => $menuItems,
    ]);
    ?>
</div>
<div class="mdc-drawer-scrim"></div>
<div id="mdc-top-app-bar" class="bg-salamat-secondary">
    <button class="material-icon nav-icon">menu</button>
    <?= Html::img(Yii::getAlias('@web/img/logo.png'), ['class' => 'salamat-logo']) ?>
</div>

<div id="mdc-page-content" class="nano">
    <div class="nano-content">
        <div class="container-custom py-3">
            <div class="row align-items-center">
                <div class="col-auto">
                <?php
                $currentRoute = "/".Yii::$app->controller->route;
                $navHistory = Yii::$app->session['nav_history'];
                if (!empty($navHistory) && (count($navHistory) > 1 || $navHistory[0][0] != $currentRoute)) {
                    echo Html::a(Html::tag('div', 'arrow_back', ['class' => 'icon material-icon']).Yii::t('general', 'Go back'), [
                        '/root/go-back',
                        'currentRoute' => $currentRoute,
                    ], [
                        'class' => 'mdc-button btn-contained bg-salamat-color mb-2',
                    ]);
                } else {
                    echo Html::button(Html::tag('div', 'arrow_back', ['class' => 'icon material-icon']).Yii::t('general', 'Go back'), [
                        'class' => 'mdc-button btn-contained bg-salamat-color mb-2',
                        'disabled' => true,
                    ]);
                }
                ?>
                </div>
                <div class="col" style="height: 4rem;">
                <?php echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); ?>
                </div>
            </div>
        </div>

        <?= $content ?>

        <div class="p-5"></div>
    </div>
</div>

<div class="modal fade" id="dark-mode-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content has-loading">
            <div id="day-night-art" class="md-theme-dark <?= $darkTheme ? 'switch' : '' ?>">
                <div class="sky day"></div>
                <div class="sky night"></div>
                <div class="sun"><?= Html::img('@web/img/day_night/sun.png') ?></div>
                <div class="moon"><?= Html::img('@web/img/day_night/moon.png') ?></div>
                <div class="mount day"></div>
                <div class="mount night"></div>
                <button class="material-icon text-primary" data-dismiss="modal" style="position: absolute; top: 1rem; left: 1rem;">arrow_back</button>
            </div>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Dark theme') ?></div>
            </div>
            <div class="modal-body">
                <p><?= Yii::t('general', 'Dark theme turns the light surfaces of the page dark, creating an experience ideal for night. Try it out!') ?></p>
                <div class="row justify-content-end">
                    <div class="col-auto">        
                        <div class="custom-control custom-switch">
                            <input type="checkbox" id="dark-theme-switch" class="custom-control-input" name="dark_theme" <?= $darkTheme ? 'checked' : '' ?>>
                            <label class="custom-control-label text-uppercase" for="dark-theme-switch"><?= Yii::t('general', 'Dark theme') ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="loading-block">
                <div class="mdc-progress-track salamat-color">
                    <div class="indicator indeterminate"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Snackbar::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
