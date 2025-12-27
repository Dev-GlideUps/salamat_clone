<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Doctor */

$this->title = Yii::t('clinic', 'New Doctor');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Doctors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
