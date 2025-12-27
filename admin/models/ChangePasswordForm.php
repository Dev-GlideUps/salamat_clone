<?php

namespace admin\models;

use Yii;
use clinic\models\ChangePasswordForm as BaseModel;
use clinic\models\User;

class ChangePasswordForm extends BaseModel
{
    public $userID;
    
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['userID'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => ['userID' => 'id']];

        return $rules;
    }

    public function updatePassword($attributeNames = null)
    {
        if ($this->validate($attributeNames)) {
            $this->user->updateAttributes([
                'password_hash' => Yii::$app->security->generatePasswordHash($this->new_password),
                'password_updated_at' => null,
            ]);
            return true;
        }

        return false;
    }

    public function getUser() {
        return User::findOne($this->userID);
    }
}