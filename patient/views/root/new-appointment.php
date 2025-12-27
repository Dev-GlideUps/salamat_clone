<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\models\Country;

$country = new Country();

$this->title = Yii::t('clinic', 'Salamat: New appointment');
?>

<?php $form = ActiveForm::begin(); ?>
<section class="container my-5">
    <div class="row">
        <div class="col">
            <div class="media mt-3 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?>
                </div>
                <div class="media-body">
                    <h1 class="mdt-h5"><?= Yii::t('clinic', 'New appointment') ?></h1>
                </div>
            </div>
            <div class="mdc-divider my-3"></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="patient-profile-image rounded-circle mb-3" style="background-image: url('<?= $patient->photoUrl ?>');"></div>
        </div>
        <div class="col-sm col-auto">
        <?php if ($patient->isNewRecord) { ?>
            <div class="row">
                <div class="col-lg-5 col-md-6"><?= $form->field($patient, 'name')->textInput(['autocomplete' => 'off']) ?></div>
                <div class="col-lg-3 col-md-5"><?= $form->field($patient, 'phone')->textInput(['autocomplete' => 'off'])->label(Yii::t('general', 'Phone number')) ?></div>
            </div>
        <?php } else { ?>
            <div class="mdt-h5 mb-2"><?= $patient->name ?></div>
            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', 'Phone number') ?>: <?= $patient->phone ?></div>
        <?php } ?>
            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('patient', 'CPR') ?>: <?= $patient->cpr ?></div>
            <div class="mdt-subtitle-2 text-secondary"><?= Yii::t('patient', 'Nationality') ?>: <?= $country->countriesList[$patient->nationality] ?></div>
            <div class="mt-2 mb-3">
            <?= Html::button(Html::tag('div', 'edit', ['class' => 'icon material-icon']).Yii::t('general', 'Change'), [
                'class' => 'mdc-button btn-outlined salamat-color',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#change-patient-dialog',
                ],
            ]) ?>
            </div>
        </div>
    </div>
    <div class="card raised-card">
        <div class="card-body">
            <div class="mdc-list-item">
                <div class="graphic bg-salamat-color">
                    <div class="material-icon">local_hospital</div>
                </div>
                <div class="text">
                    <?= $clinic->name ?>
                    <div class="secondary">
                        <?= $clinic->name_alt ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>

<div class="modal fade" id="change-patient-dialog" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= Html::beginForm(['new-appointment'], 'get', ['class' => 'modal-content']) ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('clinic', 'Enter patient information') ?></div>
            </div>
            <div class="modal-body">
                <div class="form-label-group form-group">
                    <input type="text" id="patient-cpr" class="form-control" name="cpr" autocomplete="off" placeholder="<?= Yii::t('patient', 'CPR') ?>" value="<?= $patient->cpr ?>">
                    <label for="patient-cpr"><?= Yii::t('patient', 'CPR') ?></label>
                </div>
                <div class="form-label-group form-group mb-0">
                    <select class="form-control bootstrap-select" name="nationality" id="patient-nationality" data-live-search="true">
                        <?php foreach($country->countriesList  as $key => $value) {
                            $options = [
                                'class' => 'font-italic',
                                'value' => $key,
                            ];
                            if($key == $patient->nationality) {
                                $options['selected'] = true;
                            }
                            echo Html::tag('option', $value, $options);
                        } ?>
                    </select>
                    <label for="patient-nationality"><?= Yii::t('patient', 'Nationality') ?></label>
                </div>
                <input type="hidden" class="d-none" name="clinic" value="<?= $clinic->id ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Next'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?= Html::endForm() ?>
    </div>
</div>