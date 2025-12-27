<?php

namespace clinic\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $password;
    public $new_password;
    public $confirm_password;

    public function rules()
    {
        return [
            [['password', 'new_password', 'confirm_password'], 'required'],
            [
                'password',
                function ($attribute) {
                    $this->validatePassword($attribute);
                },
            ],
            ['new_password', 'string', 'min' => 6, 'max' => 72],
            [
                'new_password',
                function ($attribute, $params) {
                    $this->validateNewPassword($attribute);
                },
            ],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('user', 'Current password'),
            'new_password' => Yii::t('user', 'New password'),
            'confirm_password' => Yii::t('user', 'Confirm new password'),
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('user', "Incorrect password"));
        }
    }

    public function validateNewPassword($attribute)
    {
        if ($this->user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('user', "New password should be different than your current password"));
        }
    }

    public function updatePassword($attributeNames = null)
    {
        if ($this->validate($attributeNames)) {
            $this->user->updateAttributes([
                'password_hash' => Yii::$app->security->generatePasswordHash($this->new_password),
                'password_updated_at' => time(),
            ]);
            return true;
        }

        return false;
    }

    public function getUser() {
        return Yii::$app->user->identity;
    }
}
