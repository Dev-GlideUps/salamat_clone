<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * A widget to render a nav drawer component
 * @param array $options: nav container html options in terms of name-value pairs.
 * @param string | array $drawerType: nav drawer type. will be added as a css class. defaults to permanent
 * supported drawer types are ['modal', 'persistent', 'permanent', 'clipped']
 *
 * @param string $primaryColor: component primary color.
 * @param string $accentColor: component accent color (unused).
 * @param array $header: nav drawer header section. example,
 *
 *      ```php
 *      [
 *          'title' => 'Header Title',
 *          'subtext' => 'Header Subtext',
 *      ];
 *      ```
 *
 *      ```php
 *      [
 *          'title' => [
 *              'label' => 'Header Title',
 *              'options' => [...],
 *          ],
 *          'subtext' => [
 *              'label' => 'Header Subtext',
 *              'options' => [...],
 *          ],
 *      ];
 *      ```
 *
 * @param array $navItems: navigation items to be rendered inside the drawer. example,
 *
 *      ```php
 *      [
 *          [
 *              'support' => [...],
 *              'label' => [
 *                  'overline' => ['string' => 'Overline', 'options' => ['class' => 'text-secondary']],
 *                  'text' => ['string' => 'Title', 'options' => ['class' => 'text-primary']],
 *                  'secondary' => ['string' => 'Secondary', 'options' => ['class' => 'text-secondary']],
 *              ],
 *              'meta' => [...],
 *              'url' => ['site/index'],
 *              'options' => [...],
 *          ],
 *          [
 *              'label' => 'Dropdown',
 *              'items' => [
 *                   ['label' => 'Level 1 - Dropdown A', 'url' => '#', 'options' => [...]],
 *                   '<div class="mdc-divider"></div>',
 *                   ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
 *              ],
 *          ],
 *          [
 *              'label' => 'Login',
 *              'url' => ['site/login'],
 *              'visible' => Yii::$app->user->isGuest
 *          ],
 *      ];
 *      ```
 *
 * Note: Multilevel dropdowns beyond Level 1 are not supported.
 * @see vip9008\MDC\helpers\static::listItem()
 * @see https://almoamen.net/MDC/components/lists.php
 *
 * @param string $encodeLabels: wether to encode $navItems labels or not. Html::encode() will be used if true. defaults to true.
 * @param string $activateItems: wether to add active class to currently active links or not. defaults to true.
 * @param string $activateParents: wether to add active class to the parent of currently active links or not. defaults to true.
 * @param string $route: current request route. defaults to null.
 * @param string $params: current request params. defaults to null.
*/

class NavDrawer extends \yii\base\Widget
{
    public $options = [];

    // nanoScroller
    public $customScroller = false;
    public $scrollerOptions = [];

    // navigation items params
    public $navItems = [];
    public $encodeLabels = true;
    public $activateItems = true;
    public $activateParents = true;
    public $route = null;
    public $params = null;

    public function init()
    {
        parent::init();

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    public function run()
    {
        $scrollerBegin = Html::beginTag('nav', $this->options);
        $scrollerEnd = Html::endTag('nav');
        if ($this->customScroller) {
            Html::addCssClass($this->scrollerOptions, 'nano');
            $scrollerBegin = Html::beginTag('nav', array_merge($this->options, $this->scrollerOptions))."\n";
            $scrollerBegin .= Html::beginTag('div', ['class' => 'nano-content'])."\n";
            $scrollerEnd = Html::endTag('div')."\n".Html::endTag('nav')."\n";
        }

        return $scrollerBegin . $this->renderItems() . $scrollerEnd;
    }

    public function renderItems()
    {
        $items = [];

        foreach ($this->navItems as $i => $item) {
            if (!ArrayHelper::getValue($item, 'visible', true)) {
                continue;
            }

            if (is_string($item)) {
                $items[] = $item;
                continue;
            }

            $options = ArrayHelper::getValue($item, 'options', []);
            // Html::addCssClass($options, $this->primaryColor);
            $item['options'] = $options;

            $items[] = $this->renderItem($item);
        }

        return implode("\n", $items);
    }

    public function renderItem($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        $listItem = [
            'support' => ArrayHelper::getValue($item, 'support', null),
            'label' => ArrayHelper::getValue($item, 'label', false),
            'meta' => ArrayHelper::getValue($item, 'meta', null),
        ];

        if (!$listItem['label']) {
            throw new InvalidConfigException("No 'label' option could be found.");
        }

        $encodeLabel = ArrayHelper::getValue($item, 'encode', $this->encodeLabels);
        $items = ArrayHelper::getValue($item, 'items', '');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $options = ArrayHelper::getValue($item, 'options', []);

        $options['encodeText'] = $encodeLabel;
        $active = $this->isItemActive($item);

        $dropdownItems = false;
        if (!empty($items) && is_array($items)) {
            foreach ($items as $subItem) {
                if ($this->isItemActive($subItem)) {
                    $active = true;
                }

                // ensure there are no sub items
                ArrayHelper::remove($subItem, 'items');
                $dropdownItems[] = $this->renderItem($subItem);
            }

            $dropdownItems = Html::tag('div', implode("\n", $dropdownItems), ['class' => 'mdc-dropdown']);
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active bold');
        }

        if ($dropdownItems !== false) {
            if (empty($listItem['meta'])) {
                $listItem['meta'] = [
                    'string' => Html::tag('div', 'keyboard_arrow_down', ['class' => 'material-icon']),
                    'options' => [
                        'class' => 'icon',
                    ]
                ];
            }

            $containerOptions = ['class' => 'mdc-list-group'];
            if ($this->activateItems && $active) {
                Html::addCssClass($containerOptions, 'expanded');
            } else {
                Html::addCssClass($containerOptions, 'collapsed');
            }

            Html::addCssClass($options, 'interactive');
            $listOptions = [
                'support' => $listItem['support'],
                'meta' => $listItem['meta'],
            ];

            $listItem = static::listItem($listItem['label'], $listOptions, $options);
            return Html::tag('div', $listItem . $dropdownItems, $containerOptions);
        } else {
            $options['tag'] = 'a';
            $options['url'] = $url;
            $listOptions = [
                'support' => $listItem['support'],
                'meta' => $listItem['meta'],
            ];
            
            return static::listItem($listItem['label'], $listOptions, $options);
        }
    }

    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            $route = substr($route, -6) == '/index' && substr($route, 0, 5) != '/root' ? substr($route, 0, -6) : $route;
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                // remove last part of route (action id)
                $trimmedRoute = ltrim(substr($route, 0, strrpos($route, '/')), '/');
                $trimmedCurrentRoute = substr($this->route, 0, strrpos($this->route, '/'));

                if (ArrayHelper::getValue($item, 'isParent', false)) {
                    if (empty($trimmedRoute) || empty($trimmedCurrentRoute) || $trimmedRoute != $trimmedCurrentRoute) {
                        return false;
                    }
                } else {
                    if (ltrim($route, '/') != $trimmedCurrentRoute) {
                        return false;
                    }
                }
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    public static function listItem($text, $listOptions = [], $options = [])
    {
        $encodeText = ArrayHelper::remove($options, 'encodeText', true);
        $support = ArrayHelper::getValue($listOptions, 'support', null);
        $meta = ArrayHelper::getValue($listOptions, 'meta', null);
        $primaryAction = ArrayHelper::getValue($listOptions, 'primaryAction', null);

        if (is_array($text)) {
            $overline = ArrayHelper::getValue($text, 'overline', []);
            if (!empty($overline)) {
                if (is_array($overline)) {
                    $_options = ArrayHelper::getValue($overline, 'options', []);
                    Html::addCssClass($_options, 'overline');
                    $string = ArrayHelper::getValue($overline, 'string', '');
                    $string = $encodeText ? Html::encode($string) : $string;
                    $overline = Html::tag('div', $string, $_options);
                } else {
                    $overline = Html::tag('div', $overline, ['class' => 'overline']);
                }
            } else {
                $overline = '';
            }
            
            $secondary = ArrayHelper::getValue($text, 'secondary', []);
            if (!empty($secondary)) {
                if (is_array($secondary)) {
                    $_options = ArrayHelper::getValue($secondary, 'options', []);
                    Html::addCssClass($_options, 'secondary');
                    $string = ArrayHelper::getValue($secondary, 'string', '');
                    $string = $encodeText ? Html::encode($string) : $string;
                    $secondary = Html::tag('div', $string, $_options);
                } else {
                    $secondary = Html::tag('div', $secondary, ['class' => 'secondary']);
                }
            } else {
                $secondary = '';
            }
            
            $text = ArrayHelper::getValue($text, 'text', []);
            if (!empty($text)) {
                if (is_array($text)) {
                    $_options = ArrayHelper::getValue($text, 'options', []);
                    Html::addCssClass($_options, 'text');
                    $string = ArrayHelper::getValue($text, 'string', '');
                    $string = $encodeText ? Html::encode($string) : $string;
                    $text = Html::tag('div', $overline . $string . $secondary, $_options);
                } else {
                    $text = Html::tag('div', $overline . $text . $secondary, ['class' => 'text']);
                }
            } else {
                $text = Html::tag('div', $overline . $secondary, ['class' => 'text']);
            }
        } else {
            $text = Html::tag('div', $text, ['class' => 'text']);
        }

        if (is_array($support)) {
            if (!empty($support)) {
                $_options = ArrayHelper::getValue($support, 'options', []);
                if (empty(ArrayHelper::getValue($_options, 'class'))) {
                    Html::addCssClass($_options, 'icon material-icon');
                }
                $support = Html::tag('div', ArrayHelper::getValue($support, 'string', ''), $_options);
            } else {
                $support = Html::tag('div', '', ['class' => 'icon material-icon']);
            }
        } else {
            if ($support === null) {
                $support = '';
            } else {
                $support = Html::tag('div', $support, ['class' => 'icon material-icon']);
            }
        }

        if (is_array($meta)) {
            if (!empty($meta)) {
                $_options = ArrayHelper::getValue($meta, 'options', []);
                Html::addCssClass($_options, 'meta');
                $meta = Html::tag('div', ArrayHelper::getValue($meta, 'string', ''), $_options);
            } else {
                $meta = '';
            }
        } else {
            if ($meta === null) {
                $meta = '';
            } else {
                $meta = Html::tag('div', $meta, ['class' => 'meta']);
            }
        }

        if ($primaryAction === null) {
                $primaryAction = '';
        } else {
            $tag = ArrayHelper::remove($primaryAction, 'tag', 'button');
            Html::addCssClass($primaryAction, 'primary-action');
            if ($tag == 'a') {
                $url = ArrayHelper::remove($primaryAction, 'url', 'javascript: ;');
                $primaryAction = Html::a('', $url, $primaryAction);
            } else {
                $primaryAction = Html::tag($tag, '', $primaryAction);
            }
        }

        $tag = ArrayHelper::remove($options, 'tag', 'div');
        Html::addCssClass($options, 'mdc-list-item');

        if ($tag == 'a') {
            $url = ArrayHelper::remove($options, 'url', '#');
            return Html::a($support . $text . $meta, $url, $options);
        }

        return Html::tag($tag, $support . $text . $primaryAction . $meta, $options);
    }
}
