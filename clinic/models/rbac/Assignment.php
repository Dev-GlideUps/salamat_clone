<?php

namespace clinic\models\rbac;

use Yii;
use yii\rbac\Item;
use clinic\components\rbac\Configs;
use clinic\components\rbac\Helper;

/**
 * Description of Assignment
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 2.5
 */
class Assignment extends \mdm\admin\BaseObject
{
    /**
     * @var integer User id
     */
    public $id;
    /**
     * @var \yii\web\IdentityInterface User
     */
    public $user;
    /**
     * @var integer Clinic id
     */
    public $clinic_id;

    /**
     * @inheritdoc
     */
    public function __construct($id, $relation = null, $config = array())
    {
        $this->id = $id;
        $this->user = $relation->user;
        $this->clinic_id = $relation->clinic_id;
        parent::__construct($config);
    }

    /**
     * Grands a roles from a user.
     * @param array $items
     * @return integer number of successful grand
     */
    public function assign($items)
    {
        $manager = Configs::authManager();
        $success = 0;
        foreach ($items as $name) {
            try {
                $item = $manager->getRole($name);
                $item = $item ?: $manager->getPermission($name);
                $manager->assign($item, $this->user->id, $this->clinic_id);
                $success++;
            } catch (\Exception $exc) {
                Yii::error($exc->getMessage(), __METHOD__);
            }
        }
        Helper::invalidate();
        return $success;
    }

    /**
     * Revokes a roles from a user.
     * @param array $items
     * @return integer number of successful revoke
     */
    public function revoke($items)
    {
        $manager = Configs::authManager();
        $success = 0;
        foreach ($items as $name) {
            try {
                $item = $manager->getRole($name);
                $item = $item ?: $manager->getPermission($name);
                $manager->revoke($item, $this->id, $this->clinic_id);
                $success++;
            } catch (\Exception $exc) {
                Yii::error($exc->getMessage(), __METHOD__);
            }
        }
        Helper::invalidate();
        return $success;
    }

    /**
     * Get all roles/permissions
     * @return array
     */
    public function getItems($separate = false)
    {
        $manager = Configs::authManager();

        $roles = [];
        foreach ($manager->getRoles() as $name => $data) {
            $roles[$name] = $data;
        }

        $permissions = [];
        foreach ($manager->getPermissions() as $name => $data) {
            if ($name[0] != '/') {
                $permissions[$name] = $data;
            }
        }

        if ($separate) {
            return [
                'roles' => $roles,
                'permissions' => $permissions,
            ];
        }

        return array_merge($roles, $permissions);
    }

    /**
     * Get all user assignments
     * @return array
     */
    public function getAssignments()
    {
        $manager = Configs::authManager();
        $items = [];

        foreach ($manager->getAssignments($this->user->id, $this->clinic_id) as $item) {
            $items[] = $item->roleName;
        }

        return $items;
    }

    public function getItemsChildren($parentList)
    {
        $manager = Configs::authManager();
        return $manager->getItemsChildren($parentList);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->user) {
            return $this->user->$name;
        }
    }
}
