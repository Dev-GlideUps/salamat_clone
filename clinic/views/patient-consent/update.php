<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PatientConsent */

$this->title = 'Update Patient Consent: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Patient Consents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="patient-consent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
