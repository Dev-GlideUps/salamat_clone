<?php

use yii\helpers\Html;
use common\grid\DetailView;

/* @var $this yii\web\View */
/* @var $model patient\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('patient', 'Patients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-5 mb-3 salamat-color">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/group.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="patient-profile">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="personal-photo mt-5">
                    <img src="<?= $model->photoUrl ?>">
                </div>
                <h5 class="text-center"><?= $model->name ?></h5>
                <h6 class="text-secondary text-center d-none"><?= $model->name_alt ?></h6>
            </div>
            <div class="col">
                <div class="card raised-card">
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id',
                                'cpr',
                                'name',
                                'name_alt',
                                'phone',
                                'gender',
                                'height',
                                'weight',
                                'photo',
                                'dob',
                                'address',
                                'created_at:datetime',
                                'updated_at:datetime',
                            ],
                        ]) ?>

                        <div class="mdc-button-group direction-reverse p-0">
                            <?= Html::a(Html::tag('div', 'update', ['class' => 'material-icon icon']) .
                                Yii::t('clinic', 'Update'), ["update", 'id' => $model->id],
                                ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>

                            <?= Html::a(Html::tag('div', 'delete', ['class' => 'material-icon icon']) .
                                Yii::t('clinic', 'Delete'), ["delete", 'id' => $model->id],
                                ['class' => 'mdc-button btn-outlined salamat-color',
                                    'data' => [
                                        'confirm' => Yii::t('patient', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ]
                                ]) ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>