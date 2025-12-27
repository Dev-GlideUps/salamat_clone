<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */

$this->title = Yii::t('patient', 'Favorite Diagnosis');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Diagnoses'), 'url' => ['/patients/diagnoses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Favorite'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/heart.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="card-body">
                    <div class="mdt-subtitle-2 text-secondary mb-2"><?= Yii::t('patient', 'Diagnosis') ?></div>
                    <div class="mdt-body"><?= $model->description ?> <span class="text-secondary"><?= empty($model->code) ? "" : "($model->code)" ?></span></div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('clinic', 'Doctor notes') ?></div>
                    <?php foreach ($model->notesArray as $note) { ?>
                        <pre class="doctor-notes mt-2"><?= $note ?></pre>
                    <?php } ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
                <?php if ($model->created_by == Yii::$app->user->identity->id) { ?>
                    <div class="mdc-divider"></div>
                    <div class="mdc-button-group direction-reverse p-3">
                        <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], [
                            'class' => 'mdc-button salamat-color',
                        ]) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
