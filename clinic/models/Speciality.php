<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%speciality}}".
 *
 * @property int $id
 * @property string $title
 * @property string $title_ar
 * @property int $created_at
 * @property int $updated_at
 */
class Speciality extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            // BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%speciality}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_ar'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['title', 'title_ar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('clinic', 'ID'),
            'title' => Yii::t('general', 'Title'),
            'title_ar' => Yii::t('general', 'Arabic title'),
            'created_at' => Yii::t('clinic', 'Created'),
            'updated_at' => Yii::t('clinic', 'Updated'),
        ];
    }
}
