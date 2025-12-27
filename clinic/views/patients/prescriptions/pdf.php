<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->context->layout = 'plain';
$this->title = Yii::t('patient', 'Prescription')." - {$model->id}";

$formatter = Yii::$app->formatter;
// $photo = empty($model->staff->photoPath) ? Yii::getAlias('@frontend/web/img/person_light.jpg') : $model->staff->photoPath;
?>

<body id="root">
    <div class="row">
        <div class="col-8">
            <?php
            if (!empty(Yii::$app->user->identity->activeClinic->logo)) {
                $file = Yii::$app->user->identity->activeClinic->logo;
                $image = Yii::getAlias("@clinic/documents/clinics/logo/$file");
                $imageData = base64_encode(file_get_contents($image));
                echo Html::img('data: '.mime_content_type($image).';base64,'.$imageData, ['style' => 'max-height: 3cm; max-width: 4cm; margin-bottom: 0.5cm;']);
            } else { ?>
            <div style="padding-right: 8pt;">
                <h5><?= $model->branch->clinic->name ?></h5>
            </div>
            <?php } ?>
            
            <div class="mdt-subtitle-2 text-secondary">Patient</div>
            <div class="mdt-body"><?= $model->patient->name ?></div>
            <p><?= $model->patient->phone ?></p>
        </div>
        <div class="col-4">
            <p class="mdt-subtitle-2 text-secondary"><?= $model->branch->contactNumber ?></p>
            <p class="mdt-subtitle-2 text-secondary"><?= $model->branch->address ?></p>

            <div class="divider" style="margin-bottom: 0.5cm;"></div>
            
            <div class="mdt-subtitle-2 text-secondary">Date</div>
            <p><?= $formatter->asDate($model->created_at, 'long') ?></p>
            
            <div class="mdt-subtitle-2 text-secondary">Doctor</div>
            <p><?= $model->doctor->name ?></p>
        </div>
    </div>
    <div style="padding: 20pt 0;"></div>
    <div class="row">
        <div class="col-7">
            <div class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Medicine</div>
        </div>
        <div class="col-3">
            <div class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Frequency</div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Duration</div>
        </div>
    </div>
    <div class="divider"></div>
    
    <?php
    foreach ($model->items as $item) {
        $comment = !empty($item->comment);
    ?>
    <div class="row">
        <div class="col-7">
            <div class="mdt-subtitle" style="padding: 8pt 0;"><?= "{$item->medicine} - {$item->strength} ({$item::formList()[$item->form]})" ?></div>
        </div>
        <div class="col-3">
            <div class="mdt-subtitle" style="padding: 8pt 0;"><?= $item->frequency ?></div>
        </div>
        <div class="col-2">
            <div class="mdt-subtitle" style="padding: 8pt 0;"><?= $item->duration ?></div>
        </div>
    </div>
    <?php if ($comment) { ?>
        <div class="mdt-subtitle-2 text-right" style="padding-bottom: 8pt;">* <?= $item->comment ?></div>
    <?php } ?>
    <div class="divider"></div>
    <?php } ?>

</body>
