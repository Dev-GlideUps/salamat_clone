<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%doctor_service}}".
 *
 * @property int $id
 * @property int $branch_id
 * @property string $title
 * @property string|null $title_alt
 * @property int $duration
 * @property float|null $price
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Doctor $branch
 */
class DoctorService extends \yii\db\ActiveRecord
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
        return '{{%doctor_service}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'branch_id', 'title', 'duration'], 'required'],
            [['doctor_id', 'branch_id', 'duration', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['title', 'title_alt'], 'string', 'max' => 255],
            ['max_appointments', 'integer', 'min' => 1],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Doctor::className(), 'targetAttribute' => ['doctor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'doctor_id' => Yii::t('clinic', 'Doctor'),
            'title' => Yii::t('general', 'Title (English)'),
            'title_alt' => Yii::t('general', 'Title (Arabic)'),
            'duration' => Yii::t('general', 'Duration'),
            'price' => Yii::t('clinic', 'Service price'),
            'max_appointments' => Yii::t('general', 'Number of appointments'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'doctor_id']);
    }
}
