<?php

namespace admin\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class SignInForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->user;
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('user', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function signIn()
    {
        if ($this->validate()) {
            $rememberTime = $this->rememberMe ? (3600 * 24 * 30) : 0;
            return Yii::$app->user->login($this->user, $rememberTime);
        }
        
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentityByEmail($this->email);
        }

        return $this->_user;
    }
}
