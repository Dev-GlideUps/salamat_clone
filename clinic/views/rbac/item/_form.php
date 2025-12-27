<?php

use vip9008\MDC\assets\CardAsset;
use vip9008\MDC\helpers\Html;
use vip9008\MDC\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use mdm\admin\components\RouteRule;
use mdm\admin\AutocompleteAsset;
use yii\helpers\Json;
use backend\components\Configs;

/* @var $this yii\web\View */
/* @var $model common\models\Institute */
/* @var $form yii\widgets\ActiveForm */

$context = $this->context;
$labels = $context->labels();
$rules = Configs::authManager()->getRules();
unset($rules[RouteRule::RULE_NAME]);

$rules = array_keys($rules);
$source = array_combine($rules, $rules);

CardAsset::register($this);

$items = $model->isNewRecord ? null : $model->getItems();

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>

<div class="auth-item-form">
    <?php $form = ActiveForm::begin([
        'id' => 'item-form',
        'themeColor' => $accentColor,
        'fieldConfig' => [
            'options' => ['class' => 'full-width'],
        ],
    ]); ?>
    <div class="mdc-card">
        <div class="mdc-card-primary">

            <div class="header <?= $primaryColor ?>">
                <div class="title">
                    <?php if ($model->isNewRecord) { ?>
                        <h5 class="text-secondary"><?= Yii::t('rbac-admin', 'New ' . $this->title) ?></h5>
                    <?php } else { ?>
                        <h5 class="text-secondary"><?= "{$model->name}" ?></h5>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col large-6 medium-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
                </div>
                <div class="col large-6 medium-6">
                    <?= $form->field($model, 'ruleName')->dropDownList($source, [
                        'prompt' => ['text' => Yii::t('general', "none"), 'options' => ['class' => 'italic text-hint']],
                        'class' => 'mdc-searchable full-width',
                        'errorMessage' => Yii::t('general', "Can't find any match!"),
                    ]) ?>
                </div>
                <div class="col large-12 medium-12">
                    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
                </div>
                <div class="col large-12 medium-12">
                    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>
                </div>
            </div>

            <?php if(!empty($items['available']) || !empty($items['assigned'])): ?>

                <div class="header <?= $primaryColor ?>">
                    <div class="title">
                        <h5 class="text-secondary"><?= Yii::t('assignment', 'Assign Roles and Permissions') ?></h5>
                    </div>
                </div>

                <?php

                $groups = [];

                foreach ($items['available'] as $item => $group)
                {

                    $checkbox = "<div class=\"full-width mdc-list-item\">" .
                        "<div class=\"blue mdc-checkbox items\" data-value=\"$item\" tabindex=\"0\">" .
                        "<input type=\"hidden\" id=\"authitem-items-available-$item\" name=\"items[$item]\" value=\"0\"></div>" .
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
                        "<input type=\"hidden\" id=\"authitem-items-assigned-$item\" name=\"items[$item]\" value=\"1\"></div>" .
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
            <?php endif; ?>



        </div>

        <div class="mdc-button-group direction-reverse">
            <?= Html::submitButton(Yii::t('rbac-admin', 'Save'), ['class' => "mdc-button btn-contained bg-$primaryColor", 'name' => 'submit-button']) ?>
            <?= Html::a(Yii::t('rbac-admin', 'Cancel'), ['index'], ['class' => "mdc-button btn-outlined bg-$accentColor"]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
