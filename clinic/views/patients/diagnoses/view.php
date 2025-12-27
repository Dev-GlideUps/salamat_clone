<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Diagnosis */

$this->title = Yii::t('patient', 'Diagnosis');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Diagnoses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->patient->name;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="mdc-list-container">
                    <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $model->patient->id]) ?>">
                        <div class="graphic" style="background-image: url(<?= $model->patient->photoThumb ?>);"></div>
                        <div class="text">
                            <?= $model->patient->name ?>
                            <div class="secondary"><?= $model->patient->name_alt ?></div>
                        </div>
                    </a>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('patient', 'Diagnosis') ?></div>
                    <div class="mdt-body my-2"><?= $model->description ?> <span class="text-secondary"><?= empty($model->code) ? "" : "($model->code)" ?></span></div>

                    <?php if (!empty($model->notes)) { ?>
                    <pre class="doctor-notes"><?= $model->notes ?></pre>
                    <?php } ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'created_by',
                                'value' => function ($model) {
                                    return $model->doctor->name;
                                },
                            ],
                            [
                                'attribute' => 'branch_id',
                                'value' => function ($model) {
                                    return $model->branch->name;
                                },
                            ],
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
                    <?= Html::a(Html::tag('div', 'healing', ['class' => 'icon material-icon']).Yii::t('patient', 'New prescription'), ['/patients/prescriptions/create', 'id' => $model->id], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php if ($model->sickLeave === null) { ?>
                    <?= Html::a(Html::tag('div', 'description', ['class' => 'icon material-icon']).Yii::t('patient', 'Create sick leave'), ['/patients/sick-leaves/create', 'id' => $model->id], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php } else { ?>
                    <?= Html::a(Html::tag('div', 'description', ['class' => 'icon material-icon']).Yii::t('patient', 'Show sick leave'), ['/patients/sick-leaves/view', 'id' => $model->sickLeave->id], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
