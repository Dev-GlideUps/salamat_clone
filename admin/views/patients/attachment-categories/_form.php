<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model patient\models\AttachmentCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="card-body">
    <div class="row">
        <div class="col-lg-4 col-md-5 col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-5 col-sm-6">
            <?= $form->field($model, 'title_alt')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="mdc-button-group direction-reverse p-0">
        <?php $cancelRoute = $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id]; ?>
        <?= Html::submitButton(Yii::t('general', 'Save'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
        <?= Html::a(Yii::t('general', 'Cancel'), $cancelRoute, ['class' => 'mdc-button btn-outlined salamat-color']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
