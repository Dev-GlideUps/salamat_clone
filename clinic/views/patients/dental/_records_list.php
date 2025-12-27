<?php

use yii\helpers\Html;

$clinic = Yii::$app->user->identity->activeClinic;
$formatter = Yii::$app->formatter;

$displayTable = true;
if ($tooth !== null) {
    $displayTable = false;
    foreach ($records as $item) {
        if ($item->teeth == $tooth) {
            $displayTable = true;
            break;
        }
    }
}
?>

<?php if (empty($records) || !$displayTable) { ?>
<div class="empty-state-graphic text-center" style="max-width: 15rem; margin: 0 auto;">
    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No history') ?></h5>
    <p class="text-hint p-0 error-message"><?= Yii::t('clinic', "Couldn't find dental records") ?></p>
</div>
<?php } else { ?>
<div class="table-responsive" style="min-height: 22rem;">
<table class="table">
    <thead>
        <tr>
            <th><?= Yii::t('clinic', 'Procedure') ?></th>
            <th><?= Yii::t('clinic', 'Branch') ?></th>
            <th><?= Yii::t('clinic', 'Doctor') ?></th>
            <th><?= Yii::t('patient', 'Procedure date') ?></th>
            <th><?= Yii::t('patient', 'Teeth') ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $item) { ?>
        <?php if ($tooth === null || $tooth == $item->teeth) { ?>
        <tr <?= strtotime($item->procedure_date) > strtotime(date('Y-m-d')) ? 'style="background-color: rgba(var(--salamat-light), var(--opacity-hint));"' : '' ?>>
            <td><?= $item->procedure->description ?></td>
            <td><?= $item->branch->clinic_id == $clinic->id ? $item->branch->name : Html::tag('div', 'lock', ['class' => 'material-icon']) ?></td>
            <td><?= $item->branch->clinic_id == $clinic->id ? $item->doctor->name : Html::tag('div', 'lock', ['class' => 'material-icon']) ?></td>
            <td><?= $formatter->asDate($item->procedure_date, 'long') ?></td>
            <td><?= $item->teeth ?></td>
            <td class="action-column text-right">
                <div class="action-buttons">
                <?php if ($item->branch->clinic_id == $clinic->id) { ?>
                    <?php if (empty($item->notes)) { ?>
                        <button class="material-icon" type="button" disabled>comment</button>
                    <?php } else { ?>
                    <div class="dropup">
                        <button class="material-icon" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">comment</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="p-3">
                                <h6><?= $item->getAttributeLabel('notes') ?></h6>
                                <div class="mdt-body" style="white-space: normal; word-wrap: break-word; line-height: 1; width: 20rem;"><?= nl2br($item->notes) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php } ?>
                </div>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
