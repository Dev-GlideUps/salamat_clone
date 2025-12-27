<?php

use common\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model clinic\models\Clinic */
/* @var $form yii\widgets\ActiveForm */
if (!empty($model->logo)) {
    $this->RegisterJs('
        $("#clinic-imagefile").siblings(".custom-file-label").first().addClass("has-photo").attr("data-photo", "'.$model->logoUrl.'").prop("style", "background-image: url(\''.$model->logoUrl.'\');");
    ', $this::POS_END);
    }
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card-body">
    <div class="row">
        <div class="col-sm-auto">
            <div class="personal-photo-input square">
                <?= $form->field($model, 'imageFile')->fileInput() ?>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'name_alt')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-auto">
                    <?= $form->field($model, 'phone')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'packageArray')->dropDownList($model::packages(), [
                        "class" => "form-control bootstrap-select",
                        "multiple" => true,
                    ]) ?>
                </div>
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'vat_account')->textInput() ?>
                </div>
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, 'tax_account')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'invoice_terms')->textarea([
                        'class' => 'form-control bootstrap-markdown',
                        'rows' => '7',
                    ]) ?>
                </div>
            </div>

            <h5 class="mt-3 mb-4">SMS notifications</h5>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'appointment_sms')->switch() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="mdc-button-group direction-reverse p-0">
        <?php $cancelRoute = $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id]; ?>
        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
        <?= Html::a(Yii::t('general', 'Cancel'), $cancelRoute, ['class' => 'mdc-button btn-outlined salamat-color']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
