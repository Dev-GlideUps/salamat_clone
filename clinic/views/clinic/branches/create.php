<?php

// use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Branch */

$this->title = Yii::t('clinic', 'Create Branch');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
