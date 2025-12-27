<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel clinic\models\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('clinic', 'Branches');
$this->params['breadcrumbs'][] = Yii::t('clinic', 'Clinic / Hospital');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-custom">
    <div class="row">
        <div class="col">
            <div class="media mt-1 mb-3">
                <div class="media-icon mr-3">
                    <?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/building.svg')) ?>
                </div>
                <div class="media-body">
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($dataProvider->models as $item) { ?>
        <div class="col-lg-4 col-md-6">
            <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="card raised-card action-card">
                <div class="card-body">
                    <h6><?= $item->name ?></h6>
                    <p class="mdt-subtitle text-secondary"><?= $item->contactNumber ?></p>
                    <div class="mdt-subtitle-2 text-secondary"><?= $item->address ?></div>
                </div>
            </a>
        </div>
        <?php } ?>
    </div>
</div>
