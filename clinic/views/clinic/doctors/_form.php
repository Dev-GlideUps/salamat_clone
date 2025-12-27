<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\models\Languages;
use clinic\models\Speciality;

$specialityList = Speciality::find()->select(["title"])->indexBy("id")->column();

/* @var $this yii\web\View */
/* @var $model clinic\models\Doctor */
/* @var $form yii\widgets\ActiveForm */

if (!empty($model->photo)) {
    $this->RegisterJs('
        $("#doctor-imagefile").siblings(".custom-file-label").first().addClass("has-photo").attr("data-photo", "'.$model->photoUrl.'").prop("style", "background-image: url(\''.$model->photoUrl.'\');");
    ', $this::POS_END);
}
?>

<div class="doctor-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-auto">
                <div class="personal-photo-input text-center">
                    <?= $form->field($model, 'imageFile')->fileInput() ?>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <?= $form->field($model, 'name')->textInput() ?>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <?= $form->field($model, 'name_alt')->textInput() ?>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <?= $form->field($model, 'mobile')->textInput() ?>
                    </div>
                    <div class="col-xl-6 col-lg-8 col-md-7">
                        <?= $form->field($model, 'languageArray')->dropDownList(Languages::list(), [
                            "class" => "form-control bootstrap-select",
                            "multiple" => true,
                        ]) ?>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <?= $form->field($model, 'speciality')->dropDownList($specialityList, [
                            "class" => "form-control bootstrap-select",
                            'prompt' => ['text' => 'none', 'options' => ['class' => 'font-italic']],
                            "data-live-search" => "true",
                        ]) ?>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <?= $form->field($model, 'experience')->textInput([
                            'class' => 'form-control bootstrap-datepicker',
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
                </div>
                <div class="row d-none">
                    <div class="col">
                        <?= $form->field($model, 'description')->textarea([
                            'class' => 'form-control bootstrap-markdown',
                        ]) ?>
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
</div>
