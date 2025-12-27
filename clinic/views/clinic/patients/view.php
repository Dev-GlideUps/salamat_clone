<?php

use clinic\models\Appointment;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model patient\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Patients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user;
$formatter = Yii::$app->formatter;

$cprError = !empty($model->errors['cpr']);
$country = new Country();
?>

<div class="container-custom patient-view">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-body">
                    <h5><?= Yii::t('patient', 'Patient profile') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($model->relative_name) && !empty($model->relative_phone)) { ?>
    <div class="card raised-card bg-salamat-secondary mb-4">
        <div class="card-body">
            <div class="media text-primary">
                <div class="media-icon mr-3"><span class="material-icon">warning</span></div>
                <div class="media-body"><p class="mdt-body">Emergency contact</p></div>
            </div>
            <div class="row">
                <div class="col-md-6 mdt-subtitle text-secondary"><?= $model->relative_name ?></div>
                <?php if (strlen($model->relative_relation) > 0) { ?>
                <div class="col-md-3 mdt-subtitle text-secondary"><?= $model::relativeList()[$model->relative_relation] ?></div>
                <?php } ?>
                <div class="col mdt-subtitle text-secondary text-tight"><?= $model->relative_phone ?></div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-6 col-md-8">
            <div class="card raised-card mb-3">
                <div class="mdc-list-container">
                    <div class="mdc-list-item">
                        <div class="graphic" style="background-image: url(<?= $model->photoThumb ?>);"></div>
                        <div class="text">
                            <?= $model->name ?>
                            <div class="secondary"><?= $model->name_alt ?></div>
                        </div>
                    </div>
                </div>

                <div class="mdc-divider mb-2"></div>
                <div class="row mx-0 mb-2">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->clinicPatient->getAttributeLabel('profile_ref') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText((empty($model->clinicPatient->profile_ref) ? null : $model->clinicPatient->profile_ref)) ?></div></div>
                </div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->clinicPatient->getAttributeLabel('created_at') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asDateTime($model->clinicPatient->created_at) ?></div></div>
                </div>
                
                <?php if (!empty($model->address)) { ?>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('address') ?></div></div>
                </div>
                <div class="row m-0">
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText($model->address) ?></div></div>
                </div>
                <?php } ?>

                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('phone') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $model->phone ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('cpr') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $model->cpr ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('nationality') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText(($model->nationality === null ? null : $country->countriesList[$model->nationality])) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('gender') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText(($model->gender === null ? null : $model::genderList()[$model->gender])) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= Yii::t('general', 'Age') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText($model->getAge(true)) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('blood_type') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText($model->blood_type) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('marital_status') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText(($model->marital_status === null ? null : $model::statusList()[$model->marital_status])) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('height') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText(($model->height === null ? null : Yii::t('general', '{height} cm', ['height' => $model->height]))) ?></div></div>
                </div>
                <div class="mdc-divider my-2"></div>
                <div class="row m-0">
                    <div class="col-4 pr-0"><div class="mdt-subtitle-2 text-secondary"><?= $model->getAttributeLabel('weight') ?></div></div>
                    <div class="col"><div class="mdt-subtitle-2"><?= $formatter->asText(($model->weight === null ? null : Yii::t('general', '{weight} Kg', ['weight' => $model->weight]))) ?></div></div>
                </div>
                <div class="mdc-divider mt-2"></div>

                <div class="mdc-button-group direction-reverse px-2">
                    <?= Html::a(Html::tag('div', 'update', ['class' => 'icon material-icon']).Yii::t('general', 'Update information'), ['update', 'id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                    <?= Html::a(Html::tag('div', '', ['class' => '']).Yii::t('general', 'Print Sticker'), ['sticker', 'id' => $model->id], ['class' => 'mdc-button salamat-color']) ?>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card raised-card mb-3">
                <ul class="nav nav-tabs" id="patient-profile-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#patient-appointments" role="tab" aria-selected="true"><?= Yii::t('clinic', 'Appointments') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#patient-invoices" role="tab" aria-selected="false"><?= Yii::t('finance', 'Invoices') ?></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade active show" id="patient-appointments" role="tabpanel">
                        <h6 class="text-secondary p-3 m-0"><?= Yii::t('clinic', 'Latest appointments') ?></h6>
                        <?php if (empty($appointments)) { ?>
                            <div class="card-body text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No appointments!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                        <div class="table-responsive m-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><span><?= Yii::t('clinic', 'Appointment date') ?></span></th>
                                    <th><span><?= Yii::t('general', 'Time') ?></span></th>
                                    <th class="action-column"><span></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $item) { ?>
                                <tr>
                                    <td><?= $formatter->asDate($item->date, 'full') ?></td>
                                    <td><?= "{$item->time} - {$item->end_time}" ?></td>
                                    <td class="action-column text-right">
                                        <div class="action-buttons">
                                            <div class="appointment-status appointment-status-<?= $item->status ?> p-0 rounded-circle d-inline-block" style="width: 1.25rem; height: 1.25rem; margin: 0.125rem;" data-toggle="tooltip" data-placement="bottom" title="<?= $item::statusList()[$item->status] ?>"></div>
                                            <?= Html::a('list_alt', ['/clinic/appointments/view', 'id' => $item->id], ['class' => 'material-icon mx-2']) ?>
                                            <?php if ($item->status == Appointment::STATUS_PENDING) {
                                                echo Html::a('edit', ['/clinic/appointments/update', 'id' => $item->id], ['class' => 'material-icon mx-2']);
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                        <?= Html::a(Html::tag('div', 'unfold_more', ['class' => 'icon material-icon']).Yii::t('clinic', 'Show all appointments'), [
                            '/clinic/appointments/list',
                            'AppointmentSearch' => ['cpr' => $model->cpr, 'date' => ''],
                        ], [
                            'class' => 'mdc-button btn-outlined full-width salamat-color rounded-0 border-0',
                        ]) ?>
                        <div class="mdc-divider"></div>
                        <?php } ?>

                        <div class="mdc-button-group direction-reverse p-3">
                            <?= Html::a(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('clinic', 'New appointment'), ['/clinic/appointments/create', 'cpr' => $model->cpr, 'nationality' => $model->nationality], ['class' => 'mdc-button salamat-color']) ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="patient-invoices" role="tabpanel">
                        <h6 class="text-secondary p-3 m-0"><?= Yii::t('finance', 'Latest invoices') ?></h6>
                        <?php if (empty($invoices)) { ?>
                            <div class="card-body text-center">
                                <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                    <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                    <h5 class="text-hint my-3"><?= Yii::t('clinic', 'No invoices!') ?></h5>
                                </div>
                            </div>
                        <?php } else { ?>
                        <div class="table-responsive m-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><span><?= Yii::t('finance', 'Date of issue') ?></span></th>
                                    <th><span><?= Yii::t('finance', 'Total amount') ?></span></th>
                                    <th><span><?= Yii::t('finance', 'Balance due') ?></span></th>
                                    <th class="action-column"><span></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoices as $item) { ?>
                                <tr>
                                    <td><?= $formatter->asDateTime($item->created_at) ?></td>
                                    <td><?= $formatter->asDecimal($item->total, 3) ?></td>
                                    <td><?= $formatter->asDecimal($item->balance, 3) ?></td>
                                    <td class="action-column text-right">
                                        <div class="action-buttons">
                                            <?= Html::a('credit_card', ['/finance/payments/index', 'InvoicePaymentSearch' => ['invoice_id' => $item->invoiceID]], ['class' => 'material-icon mx-2']) ?>
                                            <?= Html::a('list_alt', ['/finance/invoices/view', 'id' => $item->id], ['class' => 'material-icon mx-2']) ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                        <?= Html::a(Html::tag('div', 'unfold_more', ['class' => 'icon material-icon']).Yii::t('finance', 'Show all invoices'), [
                            '/finance/invoices/index',
                            'InvoiceSearch' => ['cpr' => $model->cpr],
                        ], [
                            'class' => 'mdc-button btn-outlined full-width salamat-color rounded-0 border-0',
                        ]) ?>
                        <div class="mdc-divider"></div>
                        <?php } ?>

                        <div class="mdc-button-group direction-reverse p-3">
                            <?= Html::a(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('finance', 'New invoice'), ['/finance/invoices/create', 'cpr' => $model->cpr, 'nationality' => $model->nationality], ['class' => 'mdc-button salamat-color']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card raised-card mb-3">
                <ul class="nav nav-tabs" id="patient-diagnoses-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#patient-diagnoses" role="tab" aria-selected="true"><?= Yii::t('patient', 'Diagnoses') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#patient-exam-notes" role="tab" aria-selected="false"><?= Yii::t('patient', 'Examination notes') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#patient-prescriptions" role="tab" aria-selected="false"><?= Yii::t('patient', 'Prescriptions') ?></a>
                    </li>
                    <?php if ($user->identity->activeClinic->has('dental') && $user->can('View diagnoses')) { ?>
                    <li class="nav-item">
                        <?= Html::a(Yii::t('patient', 'Dental chart'), ['/patients/dental/index', 'id' => $model->id], ['class' => 'nav-link']) ?>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#patient-attachments" role="tab" aria-selected="false"><?= Yii::t('general', 'Attachments') ?></a>
                    </li>
                    <?php
                    $user = Yii::$app->user;
                    if ($user->identity->active_clinic == 16) {
                        ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#patient-consent" role="tab" aria-selected="false"><?= Yii::t('general', 'Patient Consents') ?></a>
                    </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="tab-content">
                    <?php echo $this->render('_diagnoses', [
                        'model' => $model,
                        'diagnosis' => $diagnosis,
                    ]); ?>

                    <?php echo $this->render('_exam_notes', [
                        'model' => $model,
                        'examinationNotes' => $examinationNotes,
                    ]); ?>

                    <?php echo $this->render('_prescriptions', [
                        'prescriptions' => $prescriptions,
                    ]); ?>
                    
                    <?php echo $this->render('_attachments', [
                        'model' => $model,
                        'attachments' => $attachments,
                    ]); ?>

                    <?php echo $this->render('_consents', [
                        'dataProvider' => $patientConsentProvider,
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="dropup mdc-fab d-none">
    <?= Html::button(Html::tag('div', 'more_vert', ['class' => 'icon material-icon']), [
        'class' => 'mdc-fab-button mini bg-salamat-color',
        'data' => [
            'toggle' => 'dropdown',
            'flip' => 'false',
            'offset' => '0, -50px',
        ],
        'aria' => [
            'haspopup' => 'true',
            'expanded' => 'false',
        ],
    ]) ?>
    <div class="dropdown-menu dropdown-menu-right p-0">
        <div class="mdc-list-container">
            <?= Html::a(Html::tag('div', Yii::t('clinic', 'New appointment'), ['class' => 'text my-2']), ['/clinic/appointments/create', 'cpr' => $model->cpr, 'nationality' => $model->nationality], ['class' => 'mdc-list-item text-primary']) ?>
            <?= Html::a(Html::tag('div', Yii::t('finance', 'New invoice'), ['class' => 'text my-2']), ['/finance/invoices/create', 'cpr' => $model->cpr, 'nationality' => $model->nationality], ['class' => 'mdc-list-item text-primary']) ?>
            <div class="mdc-divider"></div>
            <?php if (Yii::$app->user->can('Create patient attachments')) { ?>
            <?= Html::a(Html::tag('div', Yii::t('general', 'New attachment'), ['class' => 'text my-2']), ['/patients/attachments/create', 'id' => $model->id], ['class' => 'mdc-list-item text-primary']) ?>
            <?php } ?>
            <?php if ($user->identity->activeClinic->has('dental') && $user->can('View diagnoses')) { ?>
            <?= Html::a(Html::tag('div', Yii::t('patient', 'Dental chart'), ['class' => 'text my-2']), ['/patients/dental/index', 'id' => $model->id], ['class' => 'mdc-list-item text-primary']) ?>
            <?php } ?>
            <div class="mdc-divider"></div>
            <?php if ($user->identity->isDoctor) { ?>
            <?= Html::a(Html::tag('div', Yii::t('clinic', 'Add examination notes'), ['class' => 'text my-2']), ['/patients/doctor-notes/create', 'id' => $model->id], ['class' => 'mdc-list-item text-primary']) ?>
            <?= Html::a(Html::tag('div', Yii::t('patient', 'New diagnosis'), ['class' => 'text my-2']), ['/patients/diagnoses/create', 'id' => $model->id], ['class' => 'mdc-list-item text-primary']) ?>
            <?php } ?>
        </div>
    </div>
</div>