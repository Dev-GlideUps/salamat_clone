<?php

namespace common\grid;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView as BaseGridView;

class GridView extends BaseGridView
{
    public $dataColumnClass = 'common\grid\DataColumn';
    public $pager = [
        'class' => 'common\widgets\LinkPager',

    ];
    public $options = ['class' => 'mdc-datatable'];
    public $tableOptions = ['class' => 'table table-hover'];
    public $layout = "{items}\n{pager}";
    public $emptyTextOptions = ['tag' => 'span'];

    public function renderSection($name)
    {
        switch ($name) {
            case '{errors}':
                return $this->renderErrors();
            case '{pager}':
                return Html::tag('div', $this->renderSummary() . $this->renderPager(), ['class' => 'row justify-content-end mx-0 mb-3']);
            case '{items}':
                return Html::tag('div', $this->renderItems(), ['class' => 'table-responsive']);
            case '{sorter}':
                return $this->renderSorter();
            default:
                return false;
        }
    }

    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof \Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }

    public function renderSummary() {
        return Html::tag('div', $this->renderCounter(), ['class' => 'col-auto']);
    }

    public function renderPager() {
        $pager = parent::renderPager();
        if (empty($pager)) {
            return '';
        }
        return Html::tag('div', $pager, ['class' => 'col-auto']);
    }

    public function renderCounter()
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '';
        }
        $summaryOptions = $this->summaryOptions;
        $tag = ArrayHelper::remove($summaryOptions, 'tag', 'span');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', '{begin, number}-{end, number} of {totalCount, number}', [
                        'begin' => $begin,
                        'end' => $end,
                        'count' => $count,
                        'totalCount' => $totalCount,
                        'page' => $page,
                        'pageCount' => $pageCount,
                    ]), $summaryOptions);
            }
        } else {
            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', '{count, number} {count, plural, one{record} other{records}}.', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        }

        return Yii::$app->getI18n()->format($summaryContent, [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ], Yii::$app->language);
    }
}
