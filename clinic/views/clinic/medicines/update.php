<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Medicine */

$this->title = Yii::t('clinic', 'Update Medicine: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Medicines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Update');
?>
<div class="medicine-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
