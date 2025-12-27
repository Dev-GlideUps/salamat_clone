<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('patient', 'Dental chart');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Patients'), 'url' => ['/clinic/patients/index']];
$this->params['breadcrumbs'][] = ['label' => $patient->name, 'url' => ['/clinic/patients/view', 'id' => $patient->id]];
if ($tooth === null) {
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index', 'id' => $patient->id]];
    $this->params['breadcrumbs'][] = $tooth;
}
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/tooth.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <div class="mdc-list-container">
                    <a class="mdc-list-item" href="<?= Url::to(['/clinic/patients/view', 'id' => $patient->id]) ?>">
                        <div class="graphic" style="background-image: url(<?= $patient->photoThumb ?>);"></div>
                        <div class="text">
                            <?= $patient->name ?>
                            <div class="secondary"><?= $patient->name_alt ?></div>
                        </div>
                    </a>
                </div>
                <div class="mdc-divider"></div>
                <div class="card-body">
                    <?= $this->render('_dental_chart', [
                        'patient' => $patient,
                        'records' => $records,
                        'tooth' => $tooth,
                    ]) ?>

                    <div class="py-4"></div>

                    <?php if ($tooth === null) { ?>
                    <h5><?= Yii::t('patient', 'All teeth') ?></h5>
                    <?php } else { ?>
                    <h5><?= Yii::t('patient', 'Tooth: {n}', ['n' => $tooth]) ?></h5>
                    <?php } ?>

                    <?= $this->render('_records_list', [
                        'records' => $records,
                        'tooth' => $tooth,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($tooth !== null && Yii::$app->user->identity->isDoctor) { ?>
<div class="mdc-fab">
    <?= Html::button(Html::tag('div', 'add', ['class' => 'icon material-icon']).Yii::t('patient', 'New procedure'), [
        'class' => 'mdc-fab-button extended bg-salamat-color',
        'data' => [
            'toggle' => 'modal',
            'target' => '#add-procedure',
        ],
    ]) ?>
</div>
<div id="add-procedure" class="modal full-screen-dialog fade" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-salamat-secondary">
                <button type="button" class="material-icon close-button" data-dismiss="modal">close</button>
                <div class="title"><?= Yii::t('patient', 'New procedure') ?></div>
            </div>
            <div class="modal-body">
                <?= $this->render('_procedures_form', [
                    'branches' => $branches,
                    'patient' => $patient,
                    'tooth' => $tooth,
                    'model' => $newRecord,
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
