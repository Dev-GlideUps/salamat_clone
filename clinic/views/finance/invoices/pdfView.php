<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\GridView;
use clinic\models\Branch;
use common\widgets\ActiveForm;
use clinic\models\InvoicePayment;

/* @var $this yii\web\View */

$this->title = Yii::t('finance', 'invoices');
$this->params['breadcrumbs'][] = Yii::t('general', 'Analytics & reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('general', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\assets\ChartsAsset::register($this);


$formatter = Yii::$app->formatter;

?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="card raised-card">
                <ul class="nav nav-tabs" id="doctor-profile-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \yii\helpers\Url::to(['/reports/invoices']) ?>" aria-selected="true"><?= Yii::t('clinic', 'Doctor information') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#branch-services" role="tab" aria-selected="false"><?= Yii::t('general', 'Patient information') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="card-body tab-pane fade " id="branch-info" role="tabpanel">
                        
                    </div>
                    <div class="card-body tab-pane fade active show" id="branch-services" role="tabpanel">
                        <div class="container-custom">
                            <div class="row">
                                <div class="col">
                                    <div class="media mt-1 mb-3">
                                        <div class="media-icon mr-3">
                                            <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/selected_file.svg')) ?>
                                        </div>
                                        <div class="media-body">
                                            <!-- <h5>PDF</h5> -->
                                        </div>
                                    </div>
                                </div>

                                <?= $this->render('_search_pdf', ['model' => $searchModel]); ?>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <?php if (count($dataProvider->models) == 0) { ?>
                                        <div class="text-center">
                                            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                                                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                                                <h5 class="text-hint my-3"><?= Yii::t('general', 'No results found') ?></h5>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="card raised-card invoice-index">
                                            <?= GridView::widget([
                                                'dataProvider' => $dataProvider,
                                                // 'filterModel' => $searchModel,
                                                'columns' => [
                                                    [
                                                        'attribute' => 'id',
                                                        'label' => Yii::t('finance', 'Invoice ID'),
                                                        'value' => function ($model) {
                                                            return substr($model->branch->name, 0, 4) . strval($model->id);
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'Doctor Name',
                                                        'label' => Yii::t('finance', 'Doctor Name'),
                                                        'value' => function ($model) {
                                                            $val =  isset($model->appointments) && count($model->appointments)>0?$model->appointments[0]->doctor->name:'';
                                                            return $val;
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'patient_id',
                                                        'label' => Yii::t('finance', 'Patient'),
                                                        'value' => function ($model) {
                                                            return Html::tag(
                                                                'div',
                                                                Html::tag('div', '', ['class' => 'graphic m-0', 'style' => "background-image: url('{$model->patient->photoThumb}');"]) .
                                                                    Html::tag(
                                                                        'div',
                                                                        $model->patient->name .
                                                                            Html::tag('div', $model->patient->name_alt, ['class' => 'secondary']),
                                                                        ['class' => 'text my-0 mr-0']
                                                                    ),
                                                                ['class' => 'mdc-list-item']
                                                            );
                                                        },
                                                        'format' => 'html',
                                                    ],
                                                    [
                                                        'attribute' => 'vat',
                                                        'format' => ['decimal', 3],
                                                    ],
                                                    // [
                                                    //     'attribute' => 'discount',
                                                    //     'format' => ['decimal', 3],
                                                    // ],
                                                    [
                                                        'attribute' => 'total',
                                                        'format' => ['decimal', 3],
                                                    ],
                                                    // [
                                                    //     'attribute' => 'balance',
                                                    //     'format' => ['decimal', 3],
                                                    // ],
                                                    // [
                                                    //     'attribute' => 'max_appointments',
                                                    //     'value' => function ($model) {
                                                    //         $completed = 0;
                                                    //         foreach ($model->appointments as $item) {
                                                    //             if ($item->status == Appointment::STATUS_COMPLETED) {
                                                    //                 $completed++;
                                                    //             }
                                                    //         }
                                                    //         return count($model->appointments) . " / {$model->max_appointments} ( {$completed} " . Appointment::statusList()[Appointment::STATUS_COMPLETED] . " )";
                                                    //     },
                                                    // ],
                                                    // [
                                                    //     'attribute' => 'branch_id',
                                                    //     'value' => function ($model) {
                                                    //         return $model->branch->name;
                                                    //     },
                                                    // ],
                                                    'created_at:datetime',
                                                    // 'updated_at',

                                                    // [
                                                    //     'class' => 'common\grid\ActionColumn',
                                                    //     'template' => "{pdf}\n{view}",
                                                    //     'buttons' => [
                                                    //         'pdf' => function ($url, $model, $key) {
                                                    //             $options = [
                                                    //                 'title' => Yii::t('general', 'PDF export'),
                                                    //                 'aria-label' => Yii::t('general', 'PDF export'),
                                                    //                 'target' => '_blank',
                                                    //                 'class' => 'material-icon mx-2',
                                                    //             ];
                                                    //             return Html::a("picture_as_pdf", $url, $options);
                                                    //         },
                                                    //     ],
                                                    // ],
                                                ],
                                            ]); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

