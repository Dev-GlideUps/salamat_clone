<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\SickLeave */

$this->title = "{$model->patient->name}";
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Sick Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
?>

<div class="container-custom sick-leave-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?> - <?= $formatter->asDate($model->created_at) ?></h5>
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
                    <h6 class="salamat-color"><?= $model->typeList()[$model->leave_type] ?></h6>
                    <div class="mdt-h6 text-secondary"><?= $model->adviseList()[$model->advise] ?></div>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'days',
                                'value' => function ($model) {
                                    return Yii::t('general', '{n} Days', ['n' => $model->days]);
                                },
                            ],
                            [
                                'attribute' => 'commencing_on',
                                'format' => ['date', 'full'],
                            ],
                            [
                                'attribute' => 'diagnosis_id',
                                'value' => function ($model) {
                                    $diag = $model->diagnosis;
                                    return empty($diag->code) ? $diag->description : "({$diag->code}) {$diag->description}";
                                },
                            ],
                            [
                                'attribute' => 'created_by',
                                'value' => function ($model) {
                                    $doc = $model->doctor;
                                    return "{$doc->name} - {$doc->specialization->title}";
                                },
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="mdc-divider"></div>
                <div class="mdc-button-group direction-reverse p-3">
                    <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['update', 'id' => $model->id], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?= Html::a(Html::tag('div', 'picture_as_pdf', ['class' => 'icon material-icon']).Yii::t('general', 'PDF export'), ['pdf', 'id' => $model->id], [
                        'class' => 'mdc-button salamat-color',
                        'target' => '_blank',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
