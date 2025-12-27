<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Prescription */

$this->title = $model->patient->name;
$this->params['breadcrumbs'][] = Yii::t('patient', 'Patients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Prescriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/two_bottles.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Yii::t('patient', 'Prescription') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card raised-card prescription-view">
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
            <div class="mdt-body my-2"><?= $model->diagnosis->description ?> <span class="text-secondary"><?= empty($model->diagnosis->code) ? "" : "({$model->diagnosis->code})" ?></span></div>
        </div>

        <div class="table-responsive m-0">
            <table class="table">
                <thead>
                    <tr>
                        <th><span><?= Yii::t('patient', 'Medicine') ?></span></th>
                        <th><span><?= Yii::t('patient', 'Strength') ?></span></th>
                        <th><span><?= Yii::t('patient', 'Form') ?></span></th>
                        <th><span><?= Yii::t('patient', 'Frequency') ?></span></th>
                        <th><span><?= Yii::t('patient', 'Duration') ?></span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($model->items as $item) {
                    $comment = !empty($item->comment);
                    ?>
                    <tr>
                        <td <?= $comment ? 'class="border-0"' : '' ?>><?= $item->medicine ?></td>
                        <td <?= $comment ? 'class="border-0"' : '' ?>><?= $item->strength ?></td>
                        <td <?= $comment ? 'class="border-0"' : '' ?>><?= $item::formList()[$item->form] ?></td>
                        <td <?= $comment ? 'class="border-0"' : '' ?>><?= $item->frequency ?></td>
                        <td <?= $comment ? 'class="border-0"' : '' ?>><?= $item->duration ?></td>
                    </tr>
                    <?php if ($comment) { ?>
                    <tr>
                        <td class="pt-0" colspan="5"><pre class="doctor-notes">* <?= $item->comment ?></pre></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

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
                ],
            ]) ?>
        </div>
                
        <div class="mdc-divider"></div>
            <div class="mdc-button-group direction-reverse p-3">
                <?= Html::a(Html::tag('div', 'picture_as_pdf', ['class' => 'icon material-icon']).Yii::t('general', 'PDF export'), ['pdf', 'id' => $model->id], [
                    'class' => 'mdc-button salamat-color',
                    'target' => '_blank',
                ]) ?>
            </div>
        </div>
        
    </div>
</div>
