<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */

$this->title = Yii::t('rbac-admin', 'Update Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');

$primaryColor = ArrayHelper::getValue(Yii::$app->params, 'primaryColor', 'indigo');
$accentColor = ArrayHelper::getValue(Yii::$app->params, 'accentColor', 'blue');
?>

<div class="space"></div>
<div class="space"></div>

<div class="container">
    <div class="row">
        <div class="col xlarge-7 large-10 medium-12">
            <section class="chapter auth-item-update">
                <h4 class="<?= $primaryColor ?>"><?= $this->title ?></h4>

                <?=
                $this->render('_form', [
                    'model' => $model,
                ]);
                ?>

            </section>
        </div>
    </div>
</div>