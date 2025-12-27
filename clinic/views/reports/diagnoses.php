<?php

use yii\helpers\Html;
// use yii\helpers\Json;
use clinic\models\Branch;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('patient', 'Diagnoses');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
$branches = Branch::find()->where(['clinic_id' => Yii::$app->user->identity->active_clinic])->select('name')->indexBy('id')->column();
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/chart_bar_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Yii::t('patient', 'Top 10 diagnoses') ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'options' => ['class' => 'card-body'],
                ]); ?>
                    <div class="row">
                        <?php if (count($branches) > 1) { ?>
                        <div class="col-lg-4 col-md-4">
                            <?= $form->field($search, 'branch_id')->dropdownList($branches, [
                                'prompt' => ['text' => Yii::t('general', 'All'), 'options' => ['class' => 'font-italic']],
                                'class' => 'form-control bootstrap-select',
                                // 'data-live-search' => 'true',
                            ]) ?>
                        </div>
                        <?php } ?>
                        <div class="col-lg-3 col-md-4">
                            <?= $form->field($search, 'starting_date')->textInput([
                                'autocomplete' => 'off',
                                'class' => 'form-control bootstrap-datepicker',
                                'data-date-end-date' => date('Y-m-d'),
                            ]) ?>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <?= $form->field($search, 'ending_date')->textInput([
                                'autocomplete' => 'off',
                                'class' => 'form-control bootstrap-datepicker',
                                'data-date-end-date' => date('Y-m-d'),
                            ])->hint(Yii::t('general', '* Inclusive')) ?>
                        </div>
                    </div>
                    <div class="mdc-button-group direction-reverse p-0">
                        <?= Html::submitButton(Yii::t('general', 'Apply filters'), ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
                    </div>
                <?php ActiveForm::end(); ?>

                <div class="mdc-divider"></div>
                <?php if (empty($diagnoses)) { ?>
                <div class="card-body">
                    <div class="py-5 text-center">
                        <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                        <h5 class="text-hint my-3"><?= Yii::t('patient', 'Total diagnoses: {total}', ['total' => 0]) ?></h5>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="mb-2"></div>
                
                <div class="card-body">
                    <?php if (!empty($search->branch_id)) { ?>
                    <h4 class="mb-4 salamat-color"><?= $branches[$search->branch_id] ?></h4>
                    <?php } else { ?>
                    <h4 class="mb-4 salamat-color"><?= Yii::t('clinic', 'All branches') ?></h4>
                    <?php } ?>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?= Yii::t('general', 'Description') ?></th>
                                <th><?= Yii::t('general', 'Code') ?></th>
                                <th><?= Yii::t('general', 'Total') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($diagnoses as $item) { ?>
                            <tr>
                                <td><?= $item['description'] ?></td>
                                <td><?= $item['code'] ?></td>
                                <td><?= $item['total'] ?></td>
                            </tr>
                            <tr>
                                <td class="p-0" colspan="3">
                                    <div class="mdc-progress-track salamat-color">
                                        <div class="indicator determinate" style="width: <?= $formatter->asDecimal($item['total'] / $totalCount * 100, 3) ?>%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="card-body">
                    <div class="mdt-h6 salamat-color"><?= Yii::t('patient', 'Total diagnoses: {total}', ['total' => $totalCount]) ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>