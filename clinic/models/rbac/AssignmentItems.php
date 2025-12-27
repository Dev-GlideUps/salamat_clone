<?php

namespace clinic\models\rbac;

use Yii;
use yii\base\Model;

/**
 *
 * @property bool[] $items
 */
class AssignmentItems extends Model
{
    public $items;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['items', 'each', 'rule' => ['boolean']],
        ];
    }
}
