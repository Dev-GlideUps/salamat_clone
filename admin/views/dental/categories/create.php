<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\dental\Category */

$this->title = Yii::t('clinic', 'Create Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
