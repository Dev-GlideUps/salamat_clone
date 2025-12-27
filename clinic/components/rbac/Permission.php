<?php

namespace clinic\components\rbac;

class Permission extends \yii\rbac\Permission
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