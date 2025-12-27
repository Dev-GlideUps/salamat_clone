<?php

namespace common\grid;

use yii\helpers\Html;
use yii\grid\DataColumn as BaseDataColumn;

class DataColumn extends BaseDataColumn
{
    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return parent::renderHeaderCellContent();
        }

        $label = $this->getHeaderCellLabel();
        if ($this->encodeLabel) {
            $label = Html::encode($label);
        }

        if ($this->attribute !== null && $this->enableSorting && ($sort = $this->grid->dataProvider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {
            $icon = '';
            // if ($sort->getAttributeOrder($this->attribute) == SORT_DESC) {
            //     $icon = Html::tag('div', 'arrow_upward', ['class' => 'material-icon']);
            // }
            // if ($sort->getAttributeOrder($this->attribute) == SORT_ASC) {
            //     $icon = Html::tag('div', 'arrow_downward', ['class' => 'material-icon']);
            // }

            $label = $sort->link($this->attribute, array_merge($this->sortLinkOptions, ['label' => $label.$icon]));
        } else {
            $label = Html::tag('span', $label);
        }

        return $label;
    }
}
