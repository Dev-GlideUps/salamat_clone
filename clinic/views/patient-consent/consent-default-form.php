<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>

<body id="root">
    <?= $consentForm->content ?>
<p>
    <span style="padding: 20pt 0;">PATIENT'S NAME: <?= $patient->name ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>PATIENT'S SIGNATURE: <img width="400" height="300" src="<?= Yii::getAlias('@web/img/'.$model->signature) ?>"></span>
</p>
<p>
    <span>DATE: <?= $model->consent_date ?></span>
</p>
    <?php
        if ($consentForm->template_type == 2) {
    ?>
    <p>
        <span>CPR: <?= $model->cpr ?></span>
    </p>
    <?php
        }
    ?>

<?php
    if ($consentForm->template_type == 5) {

?>
    <span style="font-size:12.0pt;
line-height:107%">I am the treating doctor/healthcare professional. I discussed
the above risks, benefits, and alternatives with the patient. The patient had
an opportunity to have all questions answered and was offered a copy of this
informed consent. The patient has been told to contact my office should they
have any questions or concerns after this treatment procedure.<o:p></o:p></span>

        <p>
            <span style="padding: 20pt 0;">Doctor's Name: <?= $model->doctor_name ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span>Doctor's Signature: <img width="400" height="300" src="<?= Yii::getAlias('@web/img/'.$model->doctor_signature) ?>"></span>
        </p>
        <p>
            <span>DATE: <?= $model->consent_date ?></span>
        </p>
<?php
    }
?>

</body>