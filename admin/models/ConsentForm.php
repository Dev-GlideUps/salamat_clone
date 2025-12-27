<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "consent_form".
 *
 * @property int $id
 * @property int $name
 * @property string $name_alt
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ConsentForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consent_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_alt','clinic_id'], 'required'],
            [['content','template_type'], 'safe'],
            [['created_at', 'updated_at','clinic_id'], 'integer'],
            [['name_alt'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_alt' => 'Name Alt',
            'content' => 'Content',
            'clinic_id' => 'Clinic Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id']);
    }
}
