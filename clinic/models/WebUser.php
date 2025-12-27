<?php

namespace clinic\models;

use clinic\components\rbac\Configs;

class WebUser extends \yii\web\User
{
    private $_access = [];

    /**
     * Checks if the user can perform the operation as specified by the given permission.
     *
     * Note that you must configure "authManager" application component in order to use this method.
     * Otherwise it will always return false.
     *
     * @param string $permissionName the name of the permission (e.g. "edit post") that needs access check.
     * @param array $params name-value pairs that would be passed to the rules associated
     * with the roles and permissions assigned to the user.
     * @param bool $allowCaching whether to allow caching the result of access check.
     * When this parameter is true (default), if the access check of an operation was performed
     * before, its result will be directly returned when calling this method to check the same
     * operation. If this parameter is false, this method will always call
     * [[\yii\rbac\CheckAccessInterface::checkAccess()]] to obtain the up-to-date access result. Note that this
     * caching is effective only within the same request and only works when `$params = []`.
     * @return bool whether the user can perform the operation as specified by the given permission.
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if (!$this->getIsGuest() && $this->identity->isBlocked) {
            return false;
        }

        if ($allowCaching && empty($params) && isset($this->_access[$permissionName])) {
            return $this->_access[$permissionName];
        }

        if (($manager = Configs::authManager()) === null) {
            return false;
        }

        try {
            $clinicId = $this->identity->active_clinic;
        } catch (\Exception $e) {
            $clinicId = null;
        }

        $access = $manager->checkAccess($this->getId(), $clinicId, $permissionName, $params);

        if ($allowCaching && empty($params)) {
            $this->_access[$permissionName] = $access;
        }

        return $access;
    }
}