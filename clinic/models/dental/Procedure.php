<?php

namespace clinic\models\dental;

use Yii;

/**
 * This is the model class for table "{{%dental_procedure}}".
 *
 * @property int $id
 * @property int $category_id
 * @property string $description
 * @property string|null $description_alt
 * @property string|null $code
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Category $category
 * @property Record[] $Records
 */
class Procedure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dental_procedure}}';
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
            [['category_id', 'description'], 'required'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'description_alt', 'code', 'chart_class'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'category_id' => Yii::t('general', 'Category ID'),
            'description' => Yii::t('general', 'Description (English)'),
            'description_alt' => Yii::t('general', 'Description (Arabic)'),
            'code' => Yii::t('general', 'Code'),
            'chart_class' => Yii::t('patient', 'Chart css class'),
            'created_at' => Yii::t('general', 'Created At'),
            'updated_at' => Yii::t('general', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[DentalRecords]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecords()
    {
        return $this->hasMany(Record::className(), ['procedure_id' => 'id']);
    }
}
