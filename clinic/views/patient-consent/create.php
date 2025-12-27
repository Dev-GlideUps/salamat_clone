<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PatientConsent */

$this->title = 'Create Patient Consent';
$this->params['breadcrumbs'][] = ['label' => 'Patient Consents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="employee-create">
                <?= $this->render('_form', [
                    'model' => $model,
                    'consentModel' => $consentModel,
                ]) ?>
            </div>
        </div>
    </div>
</div>
