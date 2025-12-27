<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model patient\models\Attachment */

$this->title = Yii::t('patient', 'Update Attachment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Attachments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('patient', 'Update');
?>
<div class="attachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
