<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body">

                    <?php
                    $form = \yii\widgets\ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ]
                    ])
                    ?>

                    <?= $form->field($model, 'file')->fileInput() ?>



                    <div class="form-group">
                        <br/>
                        <?php
                        echo Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-sm btn-primary']);
                        ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs('
$(\'form#w0\').submit(function() {
  $(this).find("button[type=\'submit\']").prop(\'disabled\',true);
});

', \yii\web\View::POS_END);
