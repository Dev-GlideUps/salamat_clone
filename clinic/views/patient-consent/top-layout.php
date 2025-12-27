<?php

?>
<html><head><meta charset="UTF-8"></head>
<body>
<p class="MsoNormal" align="center" style="text-align:center"><b><u><span style="font-size:18.0pt;line-height:107%">Mesotherapy Informed Consent-Cosmetic<o:p></o:p></span></u></b></p>
<p class="MsoNormal" align="center" style="text-align:center"><b><u><span style="font-size:18.0pt;line-height:107%">&nbsp;</span></u></b></p>
<p class="MsoNormal" style="text-align:justify"><b><span style="font-size:12.0pt;
line-height:107%">PATIENT NAME:  <?= $patient->name ?><o:p></o:p></span></b></p>
<p class="MsoNormal" style="text-align:justify"><b><span style="font-size:12.0pt;
line-height:107%">PHONE:<?= $model->private_number ?> &nbsp;&nbsp;&nbsp;&nbsp; DATE: <?= $model->consent_date ?><o:p></o:p></span></b></p>
<p class="MsoNormal" style="text-align:justify"><b><span style="font-size:12.0pt;
line-height:107%">SIGNATURE: <img width="400" height="300" src="<?= Yii::getAlias('@web/img/'.$model->signature) ?>"><o:p></o:p></span></b></p>
<?= $consentForm->content ?>
</body>

</html>