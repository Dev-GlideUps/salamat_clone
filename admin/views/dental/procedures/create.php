<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\dental\Procedure */

$this->title = Yii::t('clinic', 'Create Procedure');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Procedures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="procedure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
