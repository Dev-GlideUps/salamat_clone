<?php

namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager as BaseLinkPager;

class LinkPager extends BaseLinkPager
{
    public $options = [];
    public $prevPageCssClass = ['material-icon', 'prev'];
    public $prevPageLabel = 'chevron_left';
    public $nextPageCssClass = ['material-icon', 'next'];
    public $nextPageLabel = 'chevron_right';
    public $renderPageNumbers = true;
    public $activePageCssClass = 'active';
    public $disabledPageCssClass = 'disabled';

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        // $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        // if ($firstPageLabel !== false) {
        //     $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        // }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        // list($beginPage, $endPage) = $this->getPageRange();
        // for ($i = $beginPage; $i <= $endPage; ++$i) {
        //     $buttons[] = $this->renderPageButton($i + 1, $i, 'page-link', $this->disableCurrentPageButton && $i == $currentPage, $i == $currentPage);
        // }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        // $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        // if ($lastPageLabel !== false) {
        //     $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        // }

        $options = $this->options;
        // Html::addCssClass($options, 'Page_navigation_example');
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag('nav', Html::tag($tag, implode("\n", $buttons), ['class' => 'pagination']), $options);
    }

    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     * @param string $label the text label for the button
     * @param int $page the page number
     * @param string $class the CSS class for the page button.
     * @param bool $disabled whether this page button is disabled
     * @param bool $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        Html::addCssClass($options, 'page-item');
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');

        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        Html::addCssClass($linkOptions, $this->pageCssClass);
        Html::addCssClass($linkOptions, ['page-link']);
        if (!empty($class)) {
            Html::addCssClass($linkOptions, $class);
        }

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            Html::addCssClass($options, $this->disabledPageCssClass);
            ArrayHelper::merge($linkOptions, $disabledItemOptions);
        }

        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }
}
