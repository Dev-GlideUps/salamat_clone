<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use clinic\models\Branch;

/* @var $this yii\web\View */
/* @var $model clinic\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */

$activeClinic = Yii::$app->user->identity->active_clinic;
$branches = Branch::find()->where(['clinic_id' => $activeClinic])->select('name')->indexBy('id')->column();
?>

<div class="col-auto align-self-center mb-3">

    <?= Html::a('download', "javascript:void(0)", ['class' => 'material-icon mr-3 ',
        'data-toggle'=>"modal", 'data-target'=>"#exampleModal"]) ?>
<?= Html::button(Html::tag('div', 'filter_list', ['class' => 'icon material-icon']).Yii::t('general', 'Filters'), [
    'class' => 'mdc-button btn-outlined salamat-color',
    'data' => [
        'toggle' => 'modal',
        'target' => '#invoice-search',
    ],
]) ?>
</div>

<div class="col-12">
<?php
    $string = [];
    $attributes = array_merge([
        'cpr' => $model->cpr,
        'name' => $model->name,
        'phone' => $model->phone,
    ], $model->attributes);

    
    foreach($attributes as $attribute => $value) {
        if ($value !== null && strlen($value) > 0) {
            $label = $model->getAttributeLabel($attribute);
            switch ($attribute) {
                case 'branch_id': $value = $branches[$value]; break;

                default: break;
            }
            $string[] = "$label: <b>$value</b>";
        }
    }
    
    if (!empty($string)) { ?>
    <div class="alert alert-secondary show border" role="alert">
        <?= implode(" - ", $string) ?>
        <?= Html::a('close', ['index'], [
            'class' => 'material-icon close',
        ]) ?>
    </div>
<?php } ?>
</div>

<div class="modal fade" id="invoice-search" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['pdf-view'],
            'method' => 'get',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Filters') ?></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?= $form->field($model, 'check')->radioList([0=>'All',1=>'Last Day', 2 => 'Last Week', 3 => 'Last Month',4=>'custome'] )->label(false) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'from')->textInput([
                            'class' => 'form-control bootstrap-datepicker',
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>

                    <div class="col-sm-6">
                        <?= $form->field($model, 'to')->textInput([
                            'class' => 'form-control bootstrap-datepicker',
                            'autocomplete' => 'off',
                        ]) ?>
                    </div>
             
                <?php if (count($branches) > 1) {
                    echo $form->field($model, 'branch_id')->dropdownList($branches, [
                        'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                        'class' => 'form-control bootstrap-select',
                        // 'data-live-search' => 'true',
                    ]);
                } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Search'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Choose Format</h5>
      
      </div>
      <div class="modal-body">
      
          <?= Html::a('<i class="material-icons">picture_as_pdf</i>View as PDF', ["pdf-all?to={$model->to}&from={$model->from}&check={$model->check}"], ['class' => ' mr-3 ','target' => '_blank']) ?>
          
          
          <?= Html::a('<i class="material-icons">description</i>View as Excel', ["excel-all?to={$model->to}&from={$model->from}&check={$model->check}"], ['class' => ' mr-3 ','target' => '_blank']) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
