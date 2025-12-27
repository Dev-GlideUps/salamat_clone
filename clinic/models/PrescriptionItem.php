<?php

namespace clinic\models;

use Yii;

/**
 * This is the model class for table "{{%prescription_item}}".
 *
 * @property int $id
 * @property int $prescription_id
 * @property string $medicine
 * @property int $form
 * @property string|null $strength
 * @property string $frequency
 * @property string $duration
 *
 * @property Prescription $prescription
 */
class PrescriptionItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%prescription_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescription_id', 'medicine', 'form', 'frequency', 'duration'], 'required'],
            [['prescription_id', 'form'], 'integer'],
            [['medicine', 'strength', 'frequency', 'duration', 'comment'], 'string', 'max' => 255],
            [['prescription_id'], 'exist', 'targetClass' => Prescription::className(), 'targetAttribute' => ['prescription_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'prescription_id' => Yii::t('patient', 'Prescription'),
            'medicine' => Yii::t('patient', 'Medicine'),
            'form' => Yii::t('patient', 'Format'),
            'strength' => Yii::t('patient', 'Strength'),
            'frequency' => Yii::t('general', 'Frequency'),
            'duration' => Yii::t('general', 'Duration'),
            'comment' => Yii::t('general', 'Special instructions'),
        ];
    }

    public static function formList() {
        return Medicine::formList();
    }

    /**
     * Gets query for [[Prescription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescription()
    {
        return $this->hasOne(Prescription::className(), ['id' => 'prescription_id']);
    }
}
