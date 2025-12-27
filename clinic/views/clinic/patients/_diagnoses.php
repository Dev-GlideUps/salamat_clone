<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>

<div class="tab-pane fade active show" id="patient-diagnoses" role="tabpanel">
    <div class="row">
        <div class="col">
            <div class="mdc-list-container">
                <div class="mdc-list-item">
                    <div class="icon"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/diagnostics.svg')) ?></div>
                    <div class="text"><div class="mdt-h6 text-secondary"><?= Yii::t('patient', 'Diagnoses') ?></div></div>
                </div>
            </div>
        </div>
        <div class="col">
            <?php if (Yii::$app->user->identity->isDoctor) { ?>
            <div class="mdc-button-group direction-reverse p-3">
                <?= Html::a(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('patient', 'New diagnosis'), ['/patients/diagnoses/create', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                <?= Html::a(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('patient', 'Add examination notes'), ['/patients/doctor-notes/create', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php if (empty($diagnosis)) { ?>
        <div class="card-body text-center">
            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                <h5 class="text-hint my-3"><?= Yii::t('patient', 'No diagnoses!') ?></h5>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach ($diagnosis as $item) { ?>
        <div class="mdc-divider"></div>
        <div class="card-body">
            <?= Html::a($formatter->asDateTime($item->created_at), ['/patients/diagnoses/view', 'id' => $item->id], ['class' => 'mdt-subtitle-2 text-secondary']) ?>
            <div class="mdt-body my-2"><?= $item->description ?> <span class="text-secondary"><?= empty($item->code) ? "" : "($item->code)" ?></span></div>

            <?php if (!empty($item->notes)) { ?>
            <pre class="doctor-notes"><?= $item->notes ?></pre>
            <?php } ?>

            <div class="mdt-subtitle-2 mt-4"><?= $item->doctor->name ?> - <?= $item->branch->name ?></div>

            <?php if ($item->created_by == Yii::$app->user->identity->id) { ?>
            <div class="mdc-button-group direction-reverse p-0">
                <?= Html::a(Html::tag('div', 'healing', ['class' => 'icon material-icon']).Yii::t('patient', 'New prescription'), ['/patients/prescriptions/create', 'id' => $item->id], [
                    'class' => 'mdc-button salamat-color',
                ]) ?>
                <?php if ($item->sickLeave === null) { ?>
                    <?php if (time() <= strtotime('+1 day', $item->created_at)) { ?>
                    <?= Html::a(Html::tag('div', 'description', ['class' => 'icon material-icon']).Yii::t('patient', 'Create sick leave'), ['/patients/sick-leaves/create', 'id' => $item->id], [
                        'class' => 'mdc-button salamat-color',
                    ]) ?>
                    <?php } ?>
                <?php } else { ?>
                <?= Html::a(Html::tag('div', 'description', ['class' => 'icon material-icon']).Yii::t('patient', 'Show sick leave'), ['/patients/sick-leaves/view', 'id' => $item->sickLeave->id], [
                    'class' => 'mdc-button salamat-color',
                ]) ?>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    <?php } ?>
</div>
