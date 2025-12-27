<?php

namespace admin\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\widgets\Breadcrumbs as BaseBreadcrumbs;

class Breadcrumbs extends BaseBreadcrumbs
{
    public function run()
    {
        $links = [];
        if (empty($this->links)) {
            $links[] = $this->renderItem([
                'label' => Yii::t('general', 'Dashboard'),
                'class' => 'breadcrumbs-home',
            ], $this->itemTemplate);
        } else {
            if ($this->homeLink === null) {
                $links[] = $this->renderItem([
                    'label' => Yii::t('general', 'Dashboard'),
                    'class' => 'breadcrumbs-home',
                    'url' => Yii::$app->homeUrl,
                ], $this->itemTemplate);
            } elseif ($this->homeLink !== false) {
                Html::addCssClass($this->homeLink, 'breadcrumbs-home');
                $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
            }
            foreach ($this->links as $link) {
                if (!is_array($link)) {
                    $link = ['label' => $link];
                }
                $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
            }
        }

        $separator = '<li class="breadcrumb-seperator"><div class="material-icon">chevron_right</div></li>';
        echo Html::tag('nav', Html::tag($this->tag, implode($separator, $links), $this->options), ['class' => 'pt-3', 'aria-label' => 'breadcrumb']);
    }
}
