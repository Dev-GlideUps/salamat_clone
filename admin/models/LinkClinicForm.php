<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use clinic\models\Clinic;
use clinic\models\User;
use clinic\models\ClinicLink;

class LinkClinicForm extends Model
{
    public $user_id;
    public $clinic_id;

    protected $_user;
    protected $_clinic;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'clinic_id'], 'required'],
            ['user_id', 'exist', 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['clinic_id', 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            ['user_id', 'unique', 'targetClass' => ClinicLink::className(), 'targetAttribute' => ['user_id', 'clinic_id']],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function link()
    {
        if ($this->validate()) {
            $this->user->link('clinics', $this->clinic, ['created_at' => time()]);
            return true;
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
            $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }

    protected function getClinic()
    {
        if ($this->_clinic === null) {
            $this->_clinic = Clinic::findOne($this->clinic_id);
        }

        return $this->_clinic;
    }
}
