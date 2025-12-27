<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use admin\models\DiagnosisCode;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%patient_diagnosis}}".
 *
 * @property int $id
 * @property int $clinic_id
 * @property string|null $code
 * @property string $description
 * @property string|null $notes
 * @property array|null $notesArray
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class FavoriteDiagnosis extends \yii\db\ActiveRecord
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
        return '{{%clinic_favorite_diagnosis}}';
    }

    public function getNotesArray()
    {
        if (empty($this->notes)) {
            return [];
        }

        return Json::decode($this->notes);
    }

    public function setNotesArray($array)
    {
        if (empty($array)) {
            $array = [];
        }

        $filtered = array_values(array_filter($array, 'strlen'));

        $this->notes = Json::encode($filtered);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'description'], 'required'],
            [['clinic_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['notes'], 'string'],
            [['notesArray'], 'safe'],
//            ['notesArray', 'each', 'rule' => ['string']],
            [['code', 'description'], 'string', 'max' => 255],
            [['clinic_id'], 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            [['code'], 'exist', 'targetClass' => DiagnosisCode::className(), 'targetAttribute' => ['code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'clinic_id' => Yii::t('clinic', 'Clinic'),
            'code' => Yii::t('general', 'Code'),
            'description' => Yii::t('general', 'Description'),
            'notes' => Yii::t('clinic', 'Doctor notes'),
            'notesArray' => Yii::t('clinic', 'Doctor notes'),
            'created_by' => Yii::t('general', 'Created by'),
            'updated_by' => Yii::t('general', 'Updated by'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c');
    }
    
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'created_by'])->alias('d');
    }
}
