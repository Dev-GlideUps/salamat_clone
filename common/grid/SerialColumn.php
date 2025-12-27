<?php

namespace common\grid;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\SerialColumn as BaseSerialColumn;

class SerialColumn extends BaseSerialColumn
{
    public function renderHeaderCell()
    {
        $options = $this->headerOptions;
        Html::addCssClass($options, 'custom-datatable__cell');
        return Html::tag('th', Html::tag('span', $this->renderHeaderCellContent()), $options);
    }

    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        Html::addCssClass($options, 'custom-datatable__cell');
        return Html::tag('td', Html::tag('span', $this->renderDataCellContent($model, $key, $index)), $options);
    }
}