<?php

namespace clinic\models\dental;

use Yii;

/**
 * This is the model class for table "{{%dental_category}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $title_alt
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Procedure[] $procedures
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dental_category}}';
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
            [['title', 'chart_class'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'title_alt', 'chart_class'], 'string', 'max' => 255],
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
            'status' => Yii::t('general', 'Status'),
            'chart_class' => Yii::t('patient', 'Chart css class'),
            'created_at' => Yii::t('general', 'Created At'),
            'updated_at' => Yii::t('general', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[DentalProcedures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcedures()
    {
        return $this->hasMany(Procedure::className(), ['category_id' => 'id'])->alias('proc');
    }
}
