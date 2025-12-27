<?php

namespace common\widgets;

use yii\helpers\Html;

class ActiveForm extends \yii\bootstrap4\ActiveForm
{
    public $fieldClass = 'common\widgets\ActiveField';

    public function init()
    {
        Html::addCssClass($this->fieldConfig['inputOptions'], 'form-control');
        parent::init();
    }
}