<?php

namespace common\grid;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView as BaseDetailView;

class DetailView extends BaseDetailView
{
    public $options = ['class' => 'detail-view-widget'];
    public $template = '<div {options}>
                            <div class="{labelColumn}">
                                <div {captionOptions}>{label}</div>
                            </div>
                            <div class="col">
                                <div {contentOptions}>{value}</div>
                            </div>
                        </div>';
    public $rowOptions = ['class' => 'row mb-3'];
    public $labelColumn = 'col-lg-3 col-md-4';
    public $captionOptions = ['class' => 'pt-3 text-secondary'];
    public $contentOptions = ['class' => 'pt-3'];

    public function run()
    {
        $rows = [];
        $i = 0;
        foreach ($this->attributes as $attribute) {
            $rows[] = $this->renderAttribute($attribute, $i++);
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        
        echo Html::tag($tag, implode("\n", $rows), $options);
    }

    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            $captionOptions = ArrayHelper::getValue($attribute, 'captionOptions', []);
            $captionOptions = ArrayHelper::merge($this->captionOptions, $captionOptions);
            $captionOptions = Html::renderTagAttributes($captionOptions);

            $contentOptions = ArrayHelper::getValue($attribute, 'contentOptions', []);
            $contentOptions = ArrayHelper::merge($this->contentOptions, $contentOptions);
            $contentOptions = Html::renderTagAttributes($contentOptions);

            $options = ArrayHelper::getValue($attribute, 'options', []);
            $options = ArrayHelper::merge($this->rowOptions, $options);
            $options = Html::renderTagAttributes($options);

            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{value}' => $this->formatter->format($attribute['value'], $attribute['format']),
                '{labelColumn}' => $this->labelColumn,
                '{captionOptions}' => $captionOptions,
                '{contentOptions}' => $contentOptions,
                '{options}' => $options,
            ]);
        }

        return call_user_func($this->template, $attribute, $index, $this);
    }
}
