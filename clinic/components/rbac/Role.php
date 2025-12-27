<?php

namespace clinic\components\rbac;

class Role extends \yii\rbac\Role
{
    /**
     * @var string|null the item parent (if any)
     */
    public $parent;

    /**
     * @var integer|null the item sorting position
     */
    public $sort;
}