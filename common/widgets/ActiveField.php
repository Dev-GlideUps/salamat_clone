<?php

namespace common\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\bootstrap4\ActiveField
{
    public $template = "{input}\n{label}\n{error}\n{hint}";

    public $options = ['class' => 'form-label-group form-group'];

    public function textInput($options = [])
    {
        if (!isset($options['placeholder'])) {
            $options['placeholder'] = $this->model->getAttributeLabel($this->attribute);
        }

        $append = ArrayHelper::remove($options, 'input-append', null);
        if ($append !== null) {
            Html::addCssClass($this->options, ['input-group', 'input-append']);
            $this->template = "{input}\n{label}\n$append\n{error}\n{hint}";
        }

        return parent::textInput($options);
    }

    public function textInputTextAppend($options = [])
    {
        if (!isset($options['placeholder'])) {
            $options['placeholder'] = $this->model->getAttributeLabel($this->attribute);
        }
        
        Html::addCssClass($this->options, ['input-group', 'input-append']);
        $textAppend = ArrayHelper::remove($options, 'text-append', '');
        $append = '<div class="input-group-append"><div class="text-append">'.$textAppend.'</div></div>';
        $this->template = "{input}\n{label}\n$append\n{error}\n{hint}";

        return parent::textInput($options);
    }

    public function passwordInput($options = [])
    {
        if (!isset($options['placeholder'])) {
            $options['placeholder'] = $this->model->getAttributeLabel($this->attribute);
        }

        Html::addCssClass($this->options, ['input-group', 'input-append']);
        $button = '<div class="input-group-append"><button class="password-visibility-button material-icon text-secondary" type="button">visibility_off</button></div>';
        $this->template = "{input}\n{label}\n$button\n{error}\n{hint}";

        return parent::passwordInput($options);
    }

    public function hiddenInput($options = [])
    {
        Html::addCssClass($this->options, 'mb-0');
        $this->template = "{input}";
        return parent::hiddenInput($options);
    }

    public function fileInput($options = [])
    {
        $this->options['class'] = 'custom-file';
        $this->labelOptions['class'] = 'custom-file-label';
        return parent::fileInput($options);
    }

    public function textarea($options = [])
    {
        $this->template = "{label}\n{input}\n{error}\n{hint}";
        $this->options = ['class' => 'form-group'];
        return parent::textarea($options);
    }

    public function checkboxList($items, $options = [])
    {
        $this->template = "{label}\n{input}\n{error}\n{hint}";
        $this->options = ['class' => 'form-group'];
        return parent::checkboxList($items, $options);
    }

    public function radioList($items, $options = [])
    {
        $this->template = "{label}\n{input}\n{error}\n{hint}";
        $this->options = ['class' => 'form-group'];
        return parent::radioList($items, $options);
    }

    public function switch($options = [], $enclosedByLabel = false)
    {
        $this->checkTemplate = "<div class=\"custom-control custom-switch\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
        return parent::checkbox($options, $enclosedByLabel);
    }
}