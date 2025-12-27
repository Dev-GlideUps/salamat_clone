<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>

<div class="tab-pane fade" id="patient-exam-notes" role="tabpanel">
    <div class="row">
        <div class="col">
            <div class="mdc-list-container">
                <div class="mdc-list-item">
                    <div class="icon"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/clipboard.svg')) ?></div>
                    <div class="text"><div class="mdt-h6 text-secondary"><?= Yii::t('patient', 'Examination notes') ?></div></div>
                </div>
            </div>
        </div>
    </div>
    <?php if (empty($examinationNotes)) { ?>
        <div class="card-body text-center">
            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                <h5 class="text-hint my-3"><?= Yii::t('patient', 'No examination notes!') ?></h5>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach ($examinationNotes as $item) { ?>
        <div class="mdc-divider"></div>
        <div class="card-body">
            <?= Html::a($formatter->asDateTime($item->created_at), ['/patients/doctor-notes/view', 'id' => $item->id], ['class' => 'mdt-subtitle-2 text-secondary']) ?>
            <pre class="doctor-notes mt-3"><?= $item->notes ?></pre>
            <div class="mdt-subtitle-2 mt-4"><?= $item->doctor->name ?> - <?= $item->branch->name ?></div>

            <?php if ($item->created_by == Yii::$app->user->identity->id) { ?>
            <div class="mdc-button-group direction-reverse p-0">
                <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update'), ['/patients/doctor-notes/update', 'id' => $item->id], [
                    'class' => 'mdc-button salamat-color',
                ]) ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    <?php } ?>
</div>
