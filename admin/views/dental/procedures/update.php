<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\dental\Procedure */

$this->title = Yii::t('clinic', 'Update Procedure: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Procedures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Update');
?>
<div class="procedure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
