<?php

namespace clinic\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class SideNav extends \yii\base\Widget
{
    public $options = [];
    public $items = [];
    public $encodeLabels = true;
    public $activateItems = true;
    public $activateParents = true;
    public $route;
    public $params;
    public $dropDownCaret;

    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->route;
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->queryParams;
        }
        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = '<i class="kt-menu__ver-arrow la la-angle-right"></i>';
        }
        Html::addCssClass($this->options, 'kt-menu__nav');
    }

    public function run()
    {
        return $this->renderItems();
    }

    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;

        $icon = ArrayHelper::getValue($item, 'icon', '');

        $svg = ArrayHelper::getValue($item, 'svg', '');
        if (!empty($svg)) {
            $svg = Html::tag('span', file_get_contents($svg), ['class' => 'kt-menu__link-icon']);
        }

        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $label = Html::tag('span', $label, ['class' => 'kt-menu__link-text']);
        $options = ArrayHelper::getValue($item, 'options', []);
        Html::addCssClass($options, 'kt-menu__item');
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        Html::addCssClass($linkOptions, 'kt-menu__link');

        $active = ArrayHelper::remove($item, 'active', $this->isItemActive($item));

        if (empty($items)) {
            $items = '';
        } else {
            // $options['aria-haspopup'] = 'true';
            // $options['m-menu-submenu-toggle'] = 'hover';
            Html::addCssClass($options, 'kt-menu__item--submenu');
            Html::addCssClass($linkOptions, 'kt-menu__toggle');
            if ($this->dropDownCaret !== '') {
                $label .= ' ' . $this->dropDownCaret;
            }
            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderDropdown($items, $item);
            }
        }

        $label = $icon.$svg.$label;

        if ($active) {
            Html::addCssClass($options, 'kt-menu__item--active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    protected function renderDropdown($items, $parentItem)
    {
        /** @var Widget $dropdownClass */
        // $dropdownClass = $this->dropdownClass;
        // $dropDownOptions = ArrayHelper::getValue($parentItem, 'dropDownOptions', []);
        // return $dropdownClass::widget([
        //     'options' => $dropDownOptions,
        //     'items' => $items,
        //     'encodeLabels' => $this->encodeLabels,
        //     'clientOptions' => false,
        //     'view' => $this->view,
        // ]);
    }

    protected function isChildActive($items, &$active)
    {
        foreach ($items as $i => $child) {
            if (is_array($child) && !ArrayHelper::getValue($child, 'visible', true)) {
                continue;
            }
            if (ArrayHelper::remove($items[$i], 'active', false) || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'active');
                if ($this->activateParents) {
                    $active = true;
                }
            }
            $childItems = ArrayHelper::getValue($child, 'items');
            if (is_array($childItems)) {
                $activeParent = false;
                $items[$i]['items'] = $this->isChildActive($childItems, $activeParent);
                if ($activeParent) {
                    Html::addCssClass($items[$i]['options'], 'active');
                    $active = true;
                }
            }
        }
        return $items;
    }
    
    /**
     * @var $route: Item route
     * @var $this->route: Current route
     */
    protected function isItemActive($item)
    {
        if (!$this->activateItems) {
            return false;
        }

        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->uniqueId . '/' . $route;
            }

            $route = ltrim($route, '/');
            if ($route === $this->route) {
                return true;
            } else {
                // return false;
                $compare_1 = explode('/', $route);
                $compare_2 = explode('/', $this->route);
                array_pop($compare_1);
                array_pop($compare_2);

                if (implode('/', $compare_1) == 'root') {
                    return false;
                }

                if (implode('/', $compare_1) === implode('/', $compare_2)) {
                    return true;
                }
            }

            // unset($item['url']['#']);
            // if (count($item['url']) > 1) {
            //     $params = $item['url'];
            //     unset($params[0]);
            //     foreach ($params as $name => $value) {
            //         if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
            //             return false;
            //         }
            //     }
            // }
        }

        return false;
    }
}
