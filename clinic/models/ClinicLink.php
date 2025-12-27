<?php

namespace clinic\models;

use Yii;

/**
 * This is the model class for table "{{%clinic_user_relation}}".
 *
 * @property int $user_id
 * @property int $clinic_id
 * @property int $created_at
 *
 * @property ClinicUser[] $clinicUsers
 * @property ClinicUserRelation[] $clinicUserRelations
 * @property ClinicUser[] $users
 */
class ClinicLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clinic_user_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'clinic_id', 'created_at'], 'required'],
            [['user_id', 'clinic_id', 'blocked_at', 'created_at'], 'integer'],
            ['user_id', 'exist', 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['clinic_id', 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            ['user_id', 'unique', 'targetAttribute' => ['user_id', 'clinic_id'], 'message' => Yii::t('user', 'This user is already linked to the selected clinic.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User'),
            'clinic_id' => Yii::t('clinic', 'Clinic / Hospital'),
            'blocked_at' => Yii::t('user', 'Blocked'),
            'created_at' => Yii::t('general', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id']);
    }

    public function createLink()
    {
        $this->created_at = time();
        if ($this->validate()) {
            return $this->save();
        }
        
        return false;
    }

    public function block()
    {
        $this->blocked_at = time();
        if ($this->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'User account blocked'));
            return true;
        }
        Yii::$app->session->setFlash('error', Yii::t('user', 'Cannot block user account'));
        return false;
    }

    public function unblock()
    {
        $this->blocked_at = null;
        if ($this->save()) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'User account unblocked'));
            return true;
        }
        Yii::$app->session->setFlash('error', Yii::t('user', 'Cannot unblock user account'));
        return false;
    }

    public function getIsBlocked()
    {
        if ($this->blocked_at === null) {
            return false;
        }

        return true;
    }
}
