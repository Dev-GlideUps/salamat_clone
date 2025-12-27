<?php

use yii\helpers\Html;
use yii\helpers\Url;
use clinic\models\dental\Helper as ChartHelper;

$chartArray = ChartHelper::chartClasses($records);
?>
<div id="dental-chart" class="md-theme-dark">
    <div class="upper-teeth mb-5">
        <?php for ($i = 1; $i <= 16; $i++) { ?>
        <?php
        $class = '';
        $url = ['index', 'id' => $patient->id, 'tooth' => $i];
        if ($tooth == $i) {
            $class = 'active';
            unset($url['tooth']);
        }
        ?>
        <a class="tooth-block <?= $class ?>" href="<?= Url::to($url) ?>">
            <div class="tooth-number mdt-subtitle-2 text-secondary text-center p-2"><?= $i ?></div>
            <div class="tooth <?= implode(' ', $chartArray[$i]) ?>">
                <?= file_get_contents(Yii::getAlias("@common/web/img/dental_sys/tooth-$i.svg")) ?>
            </div>
        </a>
        <?php } ?>
    </div>
    <div class="lower-teeth">
        <?php for ($i = 32; $i >= 17; $i--) { ?>
        <?php
        $class = '';
        $url = ['index', 'id' => $patient->id, 'tooth' => $i];
        if ($tooth == $i) {
            $class = 'active';
            unset($url['tooth']);
        }
        ?>
        <a class="tooth-block <?= $class ?>" href="<?= Url::to($url) ?>">
            <div class="tooth <?= implode(' ', $chartArray[$i]) ?>">
                <?= file_get_contents(Yii::getAlias("@common/web/img/dental_sys/tooth-$i.svg")) ?>
            </div>
            <div class="tooth-number mdt-subtitle-2 text-secondary text-center p-2"><?= $i ?></div>
        </a>
        <?php } ?>
    </div>
</div>