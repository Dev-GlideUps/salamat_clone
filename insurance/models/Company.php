<?php

namespace insurance\models;

use Yii;

/**
 * This is the model class for table "{{%insurance_company}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_alt
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%insurance_company}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            // \yii\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'name_alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'name' => Yii::t('general', 'Name (English)'),
            'name_alt' => Yii::t('general', 'Name (Arabic)'),
            'created_at' => Yii::t('general', 'Created at'),
            'updated_at' => Yii::t('general', 'Updated at'),
        ];
    }
}
