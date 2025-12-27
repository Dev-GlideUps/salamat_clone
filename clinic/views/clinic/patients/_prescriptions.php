<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>

<div class="tab-pane fade" id="patient-prescriptions" role="tabpanel">
    <div class="mdc-list-container">
        <div class="mdc-list-item">
            <div class="icon"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/two_bottles.svg')) ?></div>
            <div class="text"><div class="mdt-h6 text-secondary"><?= Yii::t('patient', 'Prescriptions') ?></div></div>
        </div>
    </div>
    <?php if (empty($prescriptions)) { ?>
        <div class="card-body text-center">
            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                <h5 class="text-hint my-3"><?= Yii::t('patient', 'No prescriptions!') ?></h5>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach ($prescriptions as $item) { ?>
        <div class="mdc-divider"></div>
        <div class="card-body">
            <?= Html::a($formatter->asDateTime($item->created_at), ['/patients/prescriptions/view', 'id' => $item->id], ['class' => 'mdt-subtitle-2 text-secondary']) ?>
            <div class="mdt-body my-2"><?= $item->diagnosis->description ?> <span class="text-secondary"><?= empty($item->diagnosis->code) ? "" : "({$item->diagnosis->code})" ?></span></div>

            <div class="table-responsive m-0">
                <table class="table border">
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
                        foreach ($item->items as $med) {
                        $comment = !empty($med->comment);
                        ?>
                        <tr>
                            <td <?= $comment ? 'class="border-0"' : '' ?>><?= $med->medicine ?></td>
                            <td <?= $comment ? 'class="border-0"' : '' ?>><?= $med->strength ?></td>
                            <td <?= $comment ? 'class="border-0"' : '' ?>><?= $med::formList()[$med->form] ?></td>
                            <td <?= $comment ? 'class="border-0"' : '' ?>><?= $med->frequency ?></td>
                            <td <?= $comment ? 'class="border-0"' : '' ?>><?= $med->duration ?></td>
                        </tr>
                        <?php if ($comment) { ?>
                        <tr>
                            <td class="pt-0" colspan="5"><pre class="doctor-notes">* <?= $med->comment ?></pre></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mdt-subtitle-2 mt-2"><?= $item->doctor->name ?> - <?= $item->branch->name ?></div>
        </div>
        <?php } ?>
    <?php } ?>
</div>
