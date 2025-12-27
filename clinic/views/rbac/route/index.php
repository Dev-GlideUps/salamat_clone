<?php

use mdm\admin\AnimateAsset;
use vip9008\MDC\helpers\Html;
use vip9008\MDC\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\YiiAsset;
use vip9008\MDC\assets\CardAsset;

/* @var $this yii\web\View */
/* @var $routes [] */

$this->title = Yii::t('rbac-admin', 'Routes');
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
CardAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'routes' => $routes,
]);
$this->registerJs("var assign = '" . Url::to(['assign']) . "';");
$this->registerJs("var remove = '" . Url::to(['remove']) . "';");
$this->registerJs($this->render('_script.js'));
//$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>
<section class="chapter route-index">
    <div class="container">
        <div class="row">
            <div class="col">
                <?php $form = ActiveForm::begin([
                    'themeColor' => $accentColor,
                    'fieldConfig' => [
                        'options' => ['class' => 'full-width'],
                    ],
                ]); ?>
                <div class="mdc-card" style="max-width: 840px;">
                    <div class="mdc-card-primary">

                        <div class="header <?= $primaryColor ?>">
                            <div class="title">
                                <h5 class="text-secondary"><?= Yii::t('roles', 'Assign Routes') ?></h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <?= Html::textFieldInput('search', null, ['class' => 'full-width']) ?>
                            </div>
                        </div>

                        <?php

                        $checkboxes = [];

                        foreach ($routes['available'] as $item)
                        {

                            $checkbox = "<div class=\"full-width mdc-list-item\">" .
                                "<div class=\"blue mdc-checkbox routes\" data-value=\"$item\" tabindex=\"0\">" .
                                "<input type=\"hidden\" id=\"authitem-routes-available-$item\" name=\"routes[]\" value=\"0\"></div>" .
                                "<div class=\"text\">" .
                                "<label class=\"label\">$item</label>" .
                                "</div>" .
                                "</div>";

                            $checkboxes[] = $checkbox;
                        }

                        foreach ($routes['assigned'] as $item)
                        {

                            $checkbox = "<div class=\"full-width mdc-list-item\">" .
                                "<div class=\"blue mdc-checkbox routes checked\" data-value=\"$item\" tabindex=\"0\">" .
                                "<input type=\"hidden\" id=\"authitem-routes-assigned-$item\" name=\"routes[]\" value=\"1\"></div>" .
                                "<div class=\"text\">" .
                                "<label class=\"label\">$item</label>" .
                                "</div>" .
                                "</div>";

                            $checkboxes[] = $checkbox;

                        }


                        echo "<div class='row'>";
                        echo "<div class=\"col\">" .
                            "<div class=\"space\"></div>" .
                            "<div class=\"mdt-subtitle text-secondary\">" . Yii::t('roles', 'Routes') . "</div>" .
                            "</div>";

                        foreach ($checkboxes as $checkbox)
                        {

                            echo "<div class=\"col large-6 medium-6\">" .
                                "<div class=\"mdc-list-container\">";

                            echo $checkbox;

                            echo "</div>" .
                                "</div>";

                        }
                        echo "</div>";
                        echo "<div class=\"mdc-divider\"></div>";

                        ?>

                        <div class="mdc-divider"></div>

                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>