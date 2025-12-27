<?php

namespace patient\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%patient_attachment_category}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $title_alt
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property PatientAttachment[] $patientAttachments
 */
class AttachmentCategory extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%patient_attachment_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'title_alt'], 'string', 'max' => 255],
            [['title', 'title_alt'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'title' => Yii::t('general', 'Title (English)'),
            'title_alt' => Yii::t('general', 'Title (Arabic)'),
            'created_at' => Yii::t('general', 'Created At'),
            'updated_at' => Yii::t('general', 'Updated At'),
            'created_by' => Yii::t('general', 'Created By'),
            'updated_by' => Yii::t('general', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[PatientAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['category_id' => 'id']);
    }
}
