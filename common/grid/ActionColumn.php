<?php

namespace common\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn as BaseActionColumn;

class ActionColumn extends BaseActionColumn
{
    const TYPE_DEFAULT = 0;
    const TYPE_DROPDOWN = 1;

    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{view}` will be replaced by the result of
     * the callback `buttons['view']`. If a callback cannot be found, the token will be replaced with an empty string.
     *
     * As an example, to only have the view, and update button you can add the ActionColumn to your GridView columns as follows:
     *
     * ```php
     * ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],
     * ```
     *
     * @see buttons
     */
    public $template = "{view}\n{update}\n{delete}";
    /**
     * @var array html options to be applied to the [[initDefaultButton()|default button]].
     * @since 2.0.4
     */
    public $buttonOptions = [];

    public $type = 0;

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $icons = [
            'view' => 'list_alt',
            'update' => 'edit',
            'delete' => 'delete',
        ];
        // $icons = [
        //     'view' => Html::tag('span', file_get_contents(Yii::getAlias('@common/web/img/svg_icons/file.svg')), ['style' => 'float: left;']),
        //     'update' => Html::tag('span', file_get_contents(Yii::getAlias('@common/web/img/svg_icons/edit.svg')), ['style' => 'float: left;']),
        //     'delete' => Html::tag('span', file_get_contents(Yii::getAlias('@common/web/img/svg_icons/trash.svg')), ['style' => 'float: left;']),
        // ];

        $this->initDefaultButton('view', $icons['view']);
        $this->initDefaultButton('update', $icons['update']);
        $this->initDefaultButton('delete', $icons['delete']);
    }

    /**
     * Initializes the default button rendering callback for single button.
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                
                switch ($name) {
                    case 'view':
                        $title = Yii::t('general', 'Display detials');
                        break;
                    case 'update':
                        $title = Yii::t('general', 'Update information');
                        break;
                    case 'delete':
                        $title = Yii::t('general', 'Delete record');
                        $additionalOptions['onclick'] = "setGridDataRowDelete($model->id);";
                        $additionalOptions['data-toggle'] = "modal";
                        $url = '#grid-data-row-delete';
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $options = array_merge([
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);

                if ($this->type == self::TYPE_DROPDOWN) {
                    Html::addCssClass($options, ["mdc-list-item salamat-color"]);
                    return Html::a(Html::tag('div', $iconName, ['class' => 'icon material-icon']).Html::tag('div', $title, ['class' => 'text']), $url, $options);
                }

                $options['title'] = $title;

                Html::addCssClass($options, ["material-icon", "mx-2"]);
                return Html::a($iconName, $url, $options);
            };
        }
    }

    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof \Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }

        $dataContent = $this->renderDataCellContent($model, $key, $index);

        $actions = Html::tag('div', $dataContent, [
            'class' => 'action-buttons',
        ]);

        if ($this->type == self::TYPE_DROPDOWN) {
            $actions = Html::beginTag('div', [
                'class' => 'action-buttons dropdown',
            ]);

            $actions .= Html::button('more_vert', [
                'class' => 'dropdown-toggle hide-caret material-icon salamat-color',
                'data' => [
                    'toggle' => 'dropdown',
                    'boundary' => 'windows',
                ],
                'aria' => [
                    'haspopup' => 'true',
                    'expanded' => 'false',
                ],
            ]);

            $actions .= Html::beginTag('div', ['class' => 'dropdown-menu dropdown-menu-right']);
            $actions .= Html::tag('div', $dataContent, ['class' => 'mdc-list-group']);
            $actions .= Html::endTag('div');

            $actions .= Html::endTag('div');
        }
        
        Html::addCssClass($options, 'action-column text-right');

        return Html::tag('td', $actions, $options);
    }

    public function renderHeaderCell()
    {
        $options = $this->headerOptions;
        // $options = array_merge(['style' => 'width: 80px;'], $this->headerOptions);
        Html::addCssClass($options, 'action-column');
        
        return Html::tag('th', $this->renderHeaderCellContent(), $options);
    }

    protected function renderHeaderCellContent()
    {
        $label = parent::renderHeaderCellContent();
        // if ($label == '&nbsp;') {
        //     $label = Yii::t('general', 'Actions');
        // }

        return Html::tag('span', $label).
        Html::beginTag('div', [
            'id' => "grid-data-row-delete",
            'class' => "modal fade",
            'data-backdrop' => "static",
            'tabindex' => "-1",
            'role' => "dialog",
            'aria-hidden' => "true",
        ]).
            Html::beginTag('div', [
                'class' => "modal-dialog modal-dialog-centered",
                'role' => "document",
                'style' => 'white-space: normal',
            ]).
                Html::beginForm(['delete'], 'post', ['class' => 'modal-content']).
                Html::beginTag('div', ['class' => "modal-header"]).
                    Html::tag('div', Yii::t('general', 'Delete record'), ['class' => 'modal-title']).
                Html::endTag('div').
                Html::tag('input', '', [
                    'type' => 'hidden',
                    'class' => 'record-id',
                    'name' => 'id',
                ]).
                Html::tag('div', Yii::t('general', 'The selected record will be deleted permanently. This action cannot be undone.'), ['class' => 'modal-body']).
                Html::beginTag('div', ['class' => "modal-footer"]).
                    Html::button(Yii::t('general', 'Cancel'), ['class' => 'mdc-button salamat-color', 'onclick' => "setGridDataRowDelete('');", 'data-dismiss' => "modal"]).
                    Html::submitButton(Yii::t('general', 'Delete'), ['class' => 'mdc-button salamat-color']).
                Html::endTag('div').
                Html::endForm().
            Html::endTag('div').
        Html::endTag('div');
    }

    // public function renderFooterCell()
    // {
    //     return Html::tag('td', Html::tag('div', $this->renderFooterCellContent(), ['class' => 'cell-data action-container']), $this->footerOptions);
    // }
}