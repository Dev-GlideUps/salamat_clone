<?php

namespace clinic\models;

use Yii;
use yii\base\Model;

class UserProfileForm extends Model
{
    public $name;
    public $phone;
    public $dark_theme;

    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'string', 'min' => 3, 'max' => 255],
            ['name', 'required', 'message' => Yii::t('general', '{attribute} is required')],
            ['phone', 'string', 'max' => 24],
            ['phone', 'number'],
            ['dark_theme', 'boolean'],
            ['dark_theme', 'default', 'value' => 0],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Full name'),
            'phone' => Yii::t('general', 'Mobile'),
            'dark_theme' => Yii::t('general', 'Dark theme'),
        ];
    }

    public function updateProfile()
    {
        if ($this->validate()) {
            $user = $this->user;
            $user->name = $this->name;
            $user->phone = $this->phone;
            $user->dark_theme = $this->dark_theme;
            return $user->save();
        }

        return false;
    }

    public function getUser() {
        return Yii::$app->user->identity;
    }
}
