<div class="col text-center">
    <div class="empty-state-graphic" style="max-width: 15rem; margin: 0 auto;">
        <?= file_get_contents(Yii::getAlias('@common/web/img/graphics/empty-state-1.svg')) ?>
        <h5 class="text-hint my-3"><?= Yii::t('clinic', 'Not available!') ?></h5>
        <p class="text-hint p-0 error-message"><?= $message ?></p>
    </div>
</div>