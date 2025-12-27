<?php

namespace clinic\models;

use Yii;
use yii\base\Model;

/**
 *
 * @property int $user_id
 * @property int $clinic_id
 */
class ClinicSelectForm extends Model
{
    public $user_id;
    public $clinic_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'clinic_id'], 'required'],
            [['user_id', 'clinic_id'], 'integer'],
            ['clinic_id', 'exist', 'targetClass' => ClinicLink::className(), 'targetAttribute' => ['user_id', 'clinic_id']],
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
        ];
    }
}
