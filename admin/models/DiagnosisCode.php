<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%diagnosis_code}}".
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class DiagnosisCode extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diagnosis_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'description'], 'required'],
            [['code', 'description'], 'trim'],
            ['code', 'unique'],
            [['code', 'description'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'code' => Yii::t('patient', 'ICD-10 code'),
            'description' => Yii::t('patient', 'Diagnosis description'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }
}
