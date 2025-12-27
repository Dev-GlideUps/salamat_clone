<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\ActiveForm;
use common\grid\DetailView;
use clinic\models\Appointment;

/* @var $this yii\web\View */
/* @var $model clinic\models\Appointment */

$user = Yii::$app->user;
$formatter = Yii::$app->formatter;

$this->title = $formatter->asDate($model->date) . " - {$model->time}";
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('clinic', 'Appointments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container-custom appointment-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/book_open.svg')) ?>
                </div>
                <div class="media-body salamat-color">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>

            <div class="card raised-card">
                <div class="mdc-list-container">
                    <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $model->patient->id]) ?>">
                        <div class="graphic" style="background-image: url(<?= $model->patient->photoThumb ?>);"></div>
                        <div class="text">
                            <?= $model->patient->name ?>
                            <div class="secondary"><?= $model->patient->name_alt ?></div>
                        </div>
                    </a>
                </div>

                <div class="appointment-status appointment-status-<?= $model->status ?>">
                    <?= $model::statusList()[$model->status] ?>
                </div>

                <div class="card-body pt-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'patient.cpr',
                            'patient.phone',
                            [
                                'attribute' => 'date',
                                'format' => ['date', 'full'],
                            ],
                            [
                                'attribute' => 'time',
                                'value' => function ($model) {
                                    return "{$model->time} - {$model->end_time}";
                                },
                            ],
                            [
                                'attribute' => 'duration',
                                'value' => function ($model) {
                                    return Yii::t('general', '{time} minutes', ['time' => $model->duration]);
                                },
                            ],
                            [
                                'attribute' => 'notes',
                                'value' => function ($model) {
                                    return Html::tag('pre', empty($model->notes) ? '...' : $model->notes, ['class' => 'doctor-notes']);
                                },
                                'format' => 'html',
                            ],
                            'confirmed_at:datetime',
                            'check_in_at:time',
                            'doctor.name:text:'.Yii::t('clinic', 'Doctor'),
                            'branch.name:text:'.Yii::t('clinic', 'Branch'),
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>

                    <?php
                        $viewLinks = [];
                        if ($model->status == Appointment::STATUS_PENDING) {
                            $viewLinks[] = Html::a(Html::tag('div', 'edit', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('general', 'Change date'), ['class' => 'text']), ['update', 'id' => $model->id], [
                                'class' => 'mdc-list-item text-primary',
                            ]);
                        }
                        if ($model->canUpdateStatus) {
                            $viewLinks[] = Html::button(Html::tag('div', 'edit', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('general', 'Update status'), ['class' => 'text']), [
                                'class' => 'mdc-list-item text-primary',
                                'data' => [
                                    'toggle' => 'modal',
                                    'target' => '#appointment-status-update',
                                ],
                            ]);
                            if ($user->identity->isDoctor) {
                                $viewLinks[] = Html::button(Html::tag('div', 'insert_comment', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('clinic', 'Appointment notes'), ['class' => 'text']), [
                                    'class' => 'mdc-list-item text-primary',
                                    'data' => [
                                        'toggle' => 'modal',
                                        'target' => '#appointment-notes-update',
                                    ],
                                ]);
                                $viewLinks[] = Html::a(Html::tag('div', 'description', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('patient', 'Add examination notes'), ['class' => 'text']), ['/patients/doctor-notes/create', 'id' => $model->patient_id], [
                                    'class' => 'mdc-list-item text-primary',
                                ]);
                                $viewLinks[] = Html::a(Html::tag('div', 'note_add', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('patient', 'Add diagnosis'), ['class' => 'text']), ['/patients/diagnoses/create', 'id' => $model->patient_id], [
                                    'class' => 'mdc-list-item text-primary',
                                ]);
                            }
                        }
                        
                        if ($user->identity->activeClinic->has('dental') && $user->can('View diagnoses')) {
                            $viewLinks[] = Html::a(Html::tag('div', 'medical_services', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('patient', 'Dental chart'), ['class' => 'text']), ['/patients/dental/index', 'id' => $model->patient_id], [
                                'class' => 'mdc-list-item text-primary',
                            ]);
                        }
                    
                        if ($model->canCreateInvoice) {
                            $viewLinks[] = Html::a(Html::tag('div', 'receipt', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('finance', 'Create invoice'), ['class' => 'text']), ['/finance/invoices/appointment-invoice', 'id' => $model->id], [
                                'class' => 'mdc-list-item text-primary',
                            ]);
                        } elseif ($model->invoice !== null) {
                            $viewLinks[] = Html::a(Html::tag('div', 'receipt', ['class' => 'icon material-icon']).Html::tag('div', Yii::t('finance', 'Show invoice'), ['class' => 'text']), ['/finance/invoices/view', 'id' => $model->invoice->id], [
                                'class' => 'mdc-list-item text-primary',
                            ]);
                        }
                    ?>

                    <div class="mdc-button-group direction-reverse p-0">
                    <div class="dropup">
                        <button class="material-icon" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?= count($viewLinks) == 0 ? "disabled" : '' ?>>more_vert</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="mdc-list-group">
                                <?= implode("\n", $viewLinks) ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($model->canUpdateStatus) { ?>
<div class="modal fade" id="appointment-status-update" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['update-status', 'id' => $model->id],
            'method' => 'post',
            'options' => ['class' => 'modal-content'],
        ]); ?>
            <div class="modal-header">
                <div class="modal-title"><?= Yii::t('general', 'Update status') ?></div>
            </div>
            <div class="modal-body pb-0">
                <?= $form->field($model, 'status')->radioList($model::statusList())->label(false) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button salamat-color']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

    <?php if ($user->identity->isDoctor) { ?>
    <div class="modal fade" id="appointment-notes-update" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <?php $form = ActiveForm::begin([
                'action' => ['update-notes', 'id' => $model->id],
                'method' => 'post',
                'options' => ['class' => 'modal-content'],
            ]); ?>
                <div class="modal-header">
                    <div class="modal-title"><?= Yii::t('clinic', 'Update notes') ?></div>
                </div>
                <div class="modal-body pb-0">
                    <?= $form->field($model, 'notes')->textarea([
                        'style' => 'height: 10rem; resize: none;',
                    ]) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdc-button salamat-color" data-dismiss="modal"><?= Yii::t('general', 'Cancel') ?></button>
                    <?= Html::submitButton(Yii::t('general', 'Update'), ['class' => 'mdc-button salamat-color']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php } ?>
<?php } ?>