<?php

// use yii\helpers\Html;
?>

<div class="mdc-divider" style="margin-top: 0.5rem;"></div>
<div class="mdc-list-group bg-salamat-secondary" style="max-height: 14rem; overflow-y: auto">
    <button type="button" class="mdc-list-item" onclick="fill_diagnoses_description('', 'Unspecified');" data-dismiss="modal">
        <div class="text" style="white-space: normal;">Unspecified</div>
    </button>
    <?php foreach ($diagnoses as $item) { ?>
    <button type="button" class="mdc-list-item" onclick="fill_diagnoses_description('<?= $item->code ?>', '<?= $item->description ?>');" data-dismiss="modal">
        <div class="text" style="white-space: normal;">
            <?= $item->description ?>
        </div>
        <div class="meta"><?= $item->code ?></div>
    </button>
    <?php } ?>
</div>
<div class="mdc-divider"></div>