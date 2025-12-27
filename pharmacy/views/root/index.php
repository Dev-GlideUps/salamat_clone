<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use common\models\Country;

// $this->context->layout = 'plain';

$this->title = Yii::t('general', 'Salamat: Prescriptions');

$country = new Country();
?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card raised-card patient-form">
                <div class="card-body">
                    <h4 class="salamat-color pt-2 pb-3"><?= Yii::t('patient', 'Patient') ?></h4>

                    <?= Html::beginForm(['prescription'], 'get') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-label-group form-group">
                                <input type="text" id="patient-cpr" class="form-control" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'CPR') ?>">
                                <label for="patient-cpr"><?= Yii::t('patient', 'CPR/SSN') ?></label>
                            </div>
                            <div class="form-label-group form-group">
                                <select class="form-control bootstrap-select" name="nationality" id="patient-nationality" data-live-search="true">
                                    <?php foreach($country->countriesList  as $key => $value) {
                                        $options = [
                                            'class' => 'font-italic',
                                            'value' => $key,
                                        ];
                                        if($key == "BH") {
                                            $options['selected'] = true;
                                        }
                                        echo Html::tag('option', $value, $options);
                                    } ?>
                                </select>
                                <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                            </div>

                            <?= Html::submitButton(Yii::t('patient', 'Find prescription'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                        </div>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>
</div>