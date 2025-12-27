<?php

use yii\helpers\Html;
use common\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PatientConsentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Patient Consents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tab-pane fade" id="patient-consent" role="tabpanel">
<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/contact_1.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>

        <?php //= $this->render('_search', ['model' => $searchModel]); ?>
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
                <div class="card raised-card diagnosis-index">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'value' => function ($model) {
                                    return (!empty($model->patient->name)) ? $model->patient->name : '';
                                },
                                'attribute' => 'patient_id',
                            ],
                            [
                                'value' => function ($model) {
                                    return (!empty($model->consent->name)) ? $model->consent->name : '';
                                },
                                'attribute' => 'consent_id',
                            ],
                            'consent_date',
                            [
                                'class' => 'common\grid\ActionColumn',
                                'template' => "{pdf}",
                                'buttons' => [
                                    'pdf' => function ($url, $model, $key) {
                                        $options = [
                                            'title' => Yii::t('general', 'Show PDF'),
                                            'aria-label' => Yii::t('general', 'PDF export'),
                                            'target' => '_blank',
                                            'class' => 'material-icon mx-2',
                                        ];

                                        return Html::a("picture_as_pdf", ['/patient-consent/consent-pdf', 'id' => $model->id], $options);
                                    },
                                ],
                            ],


//                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>

