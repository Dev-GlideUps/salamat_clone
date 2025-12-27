<?php
?>
<html><head><meta charset="UTF-8"></head>
<body>
<span> <?= $consentForm->content ?></span>
<p></p>
<p></p>

<p class="MsoNormal" dir="RTL" style="unicode-bidi: embed;"><span lang="AR-SA" style="font-size:20.0pt;line-height:107%;mso-bidi-font-family:
Calibri;mso-bidi-theme-font:minor-latin">توقيع المراجعة :<span dir="LTR"><img width="400" height="300" src="<?= Yii::getAlias('@web/img/'.$model->signature) ?>"></span><o:p></o:p></span></p>
<p class="MsoNormal" dir="RTL" style="unicode-bidi: embed;"><span lang="AR-SA" style="font-size:20.0pt;line-height:107%;mso-bidi-font-family:
Calibri;mso-bidi-theme-font:minor-latin">&nbsp;التاريخ :<?= $model->consent_date ?> <o:p></o:p></span></p>
<p class="MsoNormal" dir="RTL" style="unicode-bidi: embed;"><span lang="AR-SA" style="font-size:20.0pt;line-height:107%;mso-bidi-font-family:
Calibri;mso-bidi-theme-font:minor-latin">الرقم الشخصي:&nbsp;<?= $model->private_number ?></span><span dir="LTR" style="font-size:20.0pt;line-height:107%;mso-bidi-font-family:Calibri;
mso-bidi-theme-font:minor-latin"><o:p></o:p></span></p>
</body>

</html>
