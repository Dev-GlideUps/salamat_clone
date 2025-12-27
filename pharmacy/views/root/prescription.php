<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model clinic\models\Prescription */

$this->title = $patient->name;
?>

<div class="container">
    <div class="card raised-card prescription-view">
        <div class="mdc-list-container">
            <div class="mdc-list-item">
                <div class="graphic" style="background-image: url(<?= Yii::getAlias('@web/img/patient.svg') ?>);"></div>
                <div class="text">
                    <?= $patient->name ?>
                    <div class="secondary"><?= $patient->name_alt ?></div>
                </div>
            </div>
        </div>
        <div class="mdc-divider"></div>
        <div class="card-body d-none">
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
                    'created_at:datetime',
                ],
            ]) ?>
        </div>
                
        <div class="mdc-divider"></div>
            <div class="mdc-button-group direction-reverse p-3">
                <?= Html::a(Html::tag('div', 'arrow_back', ['class' => 'icon material-icon']).Yii::t('general', 'Go back'), ['index'], [
                    'class' => 'mdc-button btn-contained bg-salamat-color',
                ]) ?>
            </div>
        </div>
        
    </div>
</div>
