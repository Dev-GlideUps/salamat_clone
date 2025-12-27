<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\dental\Category */

$this->title = Yii::t('clinic', 'Update Category: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Update');
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
