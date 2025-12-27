<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Medicine */

$this->title = Yii::t('clinic', 'Create Medicine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Medicines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
