<?php

use vip9008\MDC\assets\CardAsset;
use vip9008\MDC\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Assignment */
/* @var $fullnameField string */

$userName = ArrayHelper::getValue($model, $usernameField);
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = $model->id . " - " . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

CardAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$items = $model->getItems();
$this->registerJs("var assign = '" . Url::to(['assign', 'id' => $model->id]) . "';");
$this->registerJs("var revoke = '" . Url::to(['revoke', 'id' => $model->id]) . "';");
$this->registerJs($this->render('_script.js'));

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>
<div class="assignment-index">

    <section class="chapter item-view">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h4 class="<?= $primaryColor ?>"><?= Yii::t('assignment', 'Update Assignment') ?></h4>

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
                    <h5 class="text-secondary"><?= $this->title ?></h5>
                </div>
            </div>
            <?php

            $groups = [];

            foreach ($items['available'] as $item => $group)
            {

                $checkbox = "<div class=\"full-width mdc-list-item\">" .
                    "<div class=\"blue mdc-checkbox items\" data-value=\"$item\" tabindex=\"0\">" .
                    "<input type=\"hidden\" id=\"authitem-items-available-$item\" name=\"items[]\" value=\"0\"></div>" .
                    "<div class=\"text\">" .
                    "<label class=\"label\">$item</label>" .
                    "</div>" .
                    "</div>";

                $groups[$group][] = $checkbox;
            }

            foreach ($items['assigned'] as $item => $group)
            {

                $checkbox = "<div class=\"full-width mdc-list-item\">" .
                    "<div class=\"blue mdc-checkbox items checked\" data-value=\"$item\" tabindex=\"0\">" .
                    "<input type=\"hidden\" id=\"authitem-items-assigned-$item\" name=\"items[]\" value=\"1\"></div>" .
                    "<div class=\"text\">" .
                    "<label class=\"label\">$item</label>" .
                    "</div>" .
                    "</div>";

                $groups[$group][] = $checkbox;

            }

            foreach ($groups as $group => $checkboxes)
            {

//                                        if(empty($checkboxes)) continue;

                echo "<div class='row'>";
                echo "<div class=\"col\">" .
                    "<div class=\"space\"></div>" .
                    "<div class=\"mdt-subtitle text-secondary\">" . Yii::t('general', ucfirst($group)) . "</div>" .
                    "</div>";

                foreach ($checkboxes as $checkbox)
                {

                    echo "<div class=\"col medium-4\">" .
                        "<div class=\"mdc-list-container\">";

                    echo $checkbox;

                    echo "</div>" .
                        "</div>";

                }
                echo "</div>";
//                                        echo "<div class=\"mdc-divider\"></div>";

            }

            ?>

            <div class="mdc-divider"></div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </section>
</div>
