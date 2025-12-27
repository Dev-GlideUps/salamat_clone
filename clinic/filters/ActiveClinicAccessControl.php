<?php

namespace clinic\filters;

use Yii;
// use yii\filters\AccessControl;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;

/**
 * ActiveClinicAccessControl action filter to check if the session user has an active clinic
 * simply redirects to [user/select-clinic] action if the session user's active_clinic = null
 */
class ActiveClinicAccessControl extends ActionFilter
{
    /**
     * @var User|array|string|false the user object representing the authentication status or the ID of the user application component.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     * Starting from version 2.0.12, you can set it to `false` to explicitly switch this component support off for the filter.
     */
    public $user = 'user';

    /**
     * Initializes the [[rules]] array by instantiating rule objects from configurations.
     */
    public function init()
    {
        parent::init();
        if ($this->user !== false) {
            $this->user = Instance::ensure($this->user, User::className());
        }
    }

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        $user = $this->user;
        if ($user !== false && $user->getIsGuest()) {
            $user->loginRequired();
            return false;
        } elseif ($user->identity->active_clinic == null) {
            Yii::$app->getResponse()->redirect(['/user/select-clinic']);
            return false;
        }
        return true;
    }
}