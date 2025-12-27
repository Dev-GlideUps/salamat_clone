<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>

<div class="tab-pane fade" id="patient-attachments" role="tabpanel">
    <div class="row">
        <div class="col">
            <div class="mdc-list-container">
                <div class="mdc-list-item">
                    <div class="icon"><?= file_get_contents(Yii::getAlias('@common/web/img/svg_icons/attachment2.svg')) ?></div>
                    <div class="text"><div class="mdt-h6 text-secondary"><?= Yii::t('general', 'Attachments') ?></div></div>
                </div>
            </div>
        </div>
        <div class="col">
            <?php if (Yii::$app->user->can('Create patient attachments')) { ?>
            <div class="mdc-button-group direction-reverse p-3">
                <?= Html::a(Html::tag('div', 'cloud_upload', ['class' => 'icon material-icon']).Yii::t('general', 'New attachment'), ['/patients/attachments/create', 'id' => $model->id], ['class' => 'mdc-button btn-contained bg-salamat-color']) ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php if (empty($attachments)) { ?>
        <div class="card-body text-center">
            <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
                <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
                <h5 class="text-hint my-3"><?= Yii::t('general', 'No attachments!') ?></h5>
            </div>
        </div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><span><?= $attachments[0]->getAttributeLabel('category_id') ?></span></th>
                        <th><span><?= $attachments[0]->getAttributeLabel('branch_id') ?></span></th>
                        <th><span><?= $attachments[0]->getAttributeLabel('created_by') ?></span></th>
                        <th><span><?= $attachments[0]->getAttributeLabel('created_at') ?></span></th>
                        <th class="action-column"><span></span></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($attachments as $item) { ?>
                    <tr>
                        <td class="py-0">
                            <div class="mdc-list-item">
                                <div class="graphic m-0 bg-salamat-color"><div class="material-icon">insert_drive_file</div></div>
                                <div class="text my-0 mr-0"><?= $item->category->title ?></div>
                            </div>
                        </td>
                        <td class="py-0"><?= $item->branch->name ?></td>
                        <td class="py-0">
                            <div class="mdc-list-item">
                                <div class="text m-0">
                                    <?= $item->creator->name ?>
                                    <div class="secondary"><?= $item->creator->email ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-0"><?= $formatter->asDateTime($item->created_at) ?></td>
                        <td class="action-column text-right">
                            <div class="action-buttons">
                                <?= Html::a('cloud_download', ['/patients/attachments/download', 'id' => $item->id], ['class' => 'material-icon mx-2', 'target' => '_blank']) ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
