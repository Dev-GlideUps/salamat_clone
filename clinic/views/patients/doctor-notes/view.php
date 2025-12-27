<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\PatientExamNotes */

$this->title = Yii::t('patient', 'Examination notes');
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Examination notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->patient->name;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/clipboard.svg')) ?>
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
                    <div class="mdt-subtitle-2 text-secondary mb-2"><?= Yii::t('patient', 'Doctor notes') ?></div>

                    <pre class="doctor-notes"><?= $model->notes ?></pre>
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
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
