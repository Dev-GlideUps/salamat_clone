<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\SickLeave */

$this->title = Yii::t('patient', 'Create Sick Leave');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Sick Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sick-leave-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
