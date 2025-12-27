<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model patient\models\AttachmentCategory */

$this->title = Yii::t('general', 'New category');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Attachment categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/attachment2.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
            
            <div class="card raised-card speciality-create">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
