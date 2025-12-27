<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\hr\Salary */
$this->context->layout = 'plain';
$this->title = "{$model->patient->name} ".date('Y-m-d', $model->created_at);

$formatter = Yii::$app->formatter;
?>

<body id="root">
    <div class="row">
        <div class="col-4p5">
            <div class="mdt-subtitle-2 text-secondary" style="line-height: 16pt;">Kingdom of Bahrain</div>
            <div class="mdt-body" style="line-height: 18pt;"><?= $model->clinic->name ?></div>
            <div class="mdt-h6" style="margin:14pt 0 8pt; line-height: 16pt">Medical certificate</div>
            <div class="mdt-body" style="margin:8pt 0; line-height: 18pt;"><b>To whom it may concern</b></div>
        </div>
        <div class="col-3 text-center" style="height: 3cm;">
            <?php
            if (!empty(Yii::$app->user->identity->activeClinic->logo)) {
                $file = Yii::$app->user->identity->activeClinic->logo;
                $image = Yii::getAlias("@clinic/documents/clinics/logo/$file");
                $imageData = base64_encode(file_get_contents($image));
                echo Html::img('data: '.mime_content_type($image).';base64,'.$imageData, ['style' => 'max-height: 3cm; max-width: 4cm; margin-bottom: 0.5cm;']);
            }
            ?>
        </div>
        <div class="col-4p5 arabic-font">
            <div class="mdt-subtitle-2 text-secondary" style="line-height: 16pt;">مملكة البحرين</div>
            <div class="mdt-body" style="line-height: 18pt;"><?= $model->clinic->name_alt ?></div>
            <div class="mdt-h6" style="margin:14pt 0 8pt; line-height: 16pt">شهادة طبية</div>
            <div class="mdt-body" style="margin:8pt 0; line-height: 18pt;"><b>إلى من يهمه الأمر</b></div>
        </div>
    </div>

    <table style="margin-bottom: 12pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 29%">This is to certify that Mr. / Ms.</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; text-transform: capitalize; width: 42%"><?= $model->patient->name ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 29%">أشهد بأن السيد / السيدة / الآنسة</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">CPR</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; padding: 4pt;"><?= $model->patient->cpr ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">الرقم الشخصي</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">Medical record number</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; padding: 4pt;"><?= $model->patient->profileRef ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">رقم السجل الصحي</td>
        </tr>
    </table>

    <div class="row" style="margin-bottom: 12pt;">
        <div class="col-6">
            <div class="row border" style="margin-right: 6pt; padding: 4pt;">
                <div class="col-6">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Reported sick</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Accompanying patient</div>
                </div>
                <div class="col-1 text-center">
                    <div class="text-center">
                    <?php if ($model->leave_type == $model::TYPE_SICK) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                    <div class="text-center">
                    <?php if ($model->leave_type == $model::TYPE_COMPANION) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                </div>
                <div class="col-5 arabic-font">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">أبلغ بمرضه</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">مرافقاً لمريض</div>
                </div>
                
                <div class="col-3" style="width: 30%">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Time of attend</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Time of consult</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Date</div>
                </div>
                <div class="col-6 text-center" style="width: 40%;">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">&nbsp;&nbsp;&nbsp;</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">&nbsp;&nbsp;&nbsp;</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;"><?= $formatter->asDate($model->created_at, 'long') ?></div>
                </div>
                <div class="col-3 arabic-font" style="width: 30%">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">وقت الحضور</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">وقت الاستشارة</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">التاريخ</div>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="row border" style="margin-left: 6pt; padding: 4pt;">
                <div class="col-6">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Patient FIT for duty</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Patient UNFIT for duty</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Advised light duty</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">Patient needs employee's care</div>
                </div>
                <div class="col-1 text-center">
                    <div class="text-center">
                    <?php if ($model->advise == $model::ADVISE_DUTY_FIT) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                    <div class="text-center">
                    <?php if ($model->advise == $model::ADVISE_DUTY_UNFIT) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                    <div class="text-center">
                    <?php if ($model->advise == $model::ADVISE_DUTY_LIGHT) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                    <div class="text-center">
                    <?php if ($model->advise == $model::ADVISE_EMPLOYEE_CARE) {
                        echo Html::img(Yii::getAlias('@web/img/check_box.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } else {
                        echo Html::img(Yii::getAlias('@web/img/check_box_empty.svg'), ['style' => 'height: 14pt; width: 14pt;']);
                    } ?>
                    </div>
                </div>
                <div class="col-5 arabic-font">
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">لا مانع من مواصلة العمل</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">ينصح بعدم مواصلة العمل</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">ينصح بعمل خفيف</div>
                    <div class="mdt-subtitle-2" style="font-size: 9pt; line-height: 14pt;">المريض بحاجة لرعاية الموظف</div>
                </div>
            </div>
        </div>
    </div>

    <table style="margin-bottom: 12pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 29%">No. of days off (in figures)</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; width: 42%"><?= $model->days ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 29%">مدة الإجازة (بالأرقام)</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">No. of days off (in words)</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; text-transform: capitalize; padding: 4pt;"><?= $formatter->asSpellout($model->days) ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">مدة الإجازة (بالحروف)</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">Commencing on</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; padding: 4pt;"><?= $formatter->asDate($model->commencing_on, 'long') ?></td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">اعتباراً من</td>
        </tr>
    </table>

    <table style="margin-bottom: 12pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 13%">Diagnosis</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; width: 74%">
                <span class="text-secondary"><?= $model->diagnosis->code ?></span>
                <?= $model->diagnosis->description ?>
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 13%">التشخيص</td>
        </tr>
    </table>

    <table style="margin-bottom: 12pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 35%">Patient admitted to hospital on</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; width: 30%">
                &nbsp;&nbsp;&nbsp;
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 35%">ادخل المريض المستشفى بتاريخ</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">Patient discharged from hospital on</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt;">
                &nbsp;&nbsp;&nbsp;
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">خرج المريض من المستشفى بتاريخ</td>
        </tr>
    </table>

    <table style="margin-bottom: 12pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 22%">Doctor's name</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; text-transform: capitalize; width: 56%">
                <?= $model->doctor->name ?>
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 22%">اسم الطبيب</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">License number</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt;">
                &nbsp;&nbsp;&nbsp;
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">رقم ترخيص مزاولة المهنة</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">Speciality</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt;">
                <?= $model->doctor->specialization->title ?>
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">التخصص</td>
        </tr>
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt;">Date</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt;">
                <?= $formatter->asDate($model->created_at, 'long') ?>
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt;">التاريخ</td>
        </tr>
    </table>

    <div class="row text-secondary" style="margin: 2.5cm 0 4pt;">
        <div class="col-3">
            <div class="mdt-subtitle-2 text-right" style="line-height: 18pt; padding: 0 8pt;"><b>Official stamp</b></div>
        </div>
        <div class="col-3 arabic-font">
            <div class="mdt-subtitle-2 text-left" style="line-height: 18pt; padding: 0 8pt;"><b>الختم الرسمي</b></div>
        </div>
        <div class="col-3">
            <div class="mdt-subtitle-2 text-right" style="line-height: 18pt; padding: 0 8pt;"><b>Doctor stamp</b></div>
        </div>
        <div class="col-3 arabic-font">
            <div class="mdt-subtitle-2 text-left" style="line-height: 18pt; padding: 0 8pt;"><b>ختم الطبيب</b></div>
        </div>
    </div>

    <table style="margin-bottom: 6pt;">
        <tr>
            <td class="mdt-subtitle-2" style="line-height: 18pt; width: 35%">Certificate registration number</td>
            <td class="mdt-subtitle-2 text-center" style="line-height: 18pt; width: 30%">
                <?= $model->id ?>
            </td>
            <td class="mdt-subtitle-2 arabic-font" style="line-height: 18pt; width: 35%">رقم تسجيل الشهادة</td>
        </tr>
    </table>
    <div class="mdt-subtitle-2 text-secondary" style="font-size: 9pt;">
        Address: <?= $model->branch->address ?>.
        Telephone: <?= $model->branch->contactNumber ?>
    </div>
</body>
