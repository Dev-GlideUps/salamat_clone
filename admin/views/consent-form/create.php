<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsentForm */

$this->title = 'Add Consent Form';
$this->params['breadcrumbs'][] = ['label' => 'Consent Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consent-form-create">

<div class="container-custom user-create">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/address_book_2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'clinics' => $clinics,
                ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

 

</div>
