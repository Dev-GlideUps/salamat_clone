<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsentForm */

$this->title = 'Create Consent Form';
$this->params['breadcrumbs'][] = ['label' => 'Consent Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consent-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
