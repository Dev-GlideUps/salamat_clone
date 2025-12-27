<?php

namespace clinic\models;

use Yii;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%clinic_medicine}}".
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string|null $forms
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Clinic $clinic
 */
class Medicine extends \yii\db\ActiveRecord
{
    const FORM_LIQUID = 0;
    const FORM_TABLET = 1;
    const FORM_DROP = 2;
    const FORM_SUPPOSITORY = 3;
    const FORM_TOPICAL = 4;
    const FORM_INHALER = 5;
    const FORM_INJECTION_SUBCUTANEOUS = 6;
    const FORM_INJECTION_INTRAMUSCULAR = 7;
    const FORM_INJECTION_INTRAVENOUS = 8;
    const FORM_INJECTION_INTRATHECAL = 9;
    const FORM_PATCH = 10;
    const FORM_BUCCAL = 11;
    const FORM_SUBLINGUAL = 12;
    const FORM_CAPSULE = 13;
    const FORM_SPRAY = 14;

    public $formats;

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
        return '{{%clinic_medicine}}';
    }

    public static function formList() {
        return [
            self::FORM_LIQUID => Yii::t('clinic', 'Liquid'),
            self::FORM_TABLET => Yii::t('clinic', 'Tablets'),
            self::FORM_CAPSULE => Yii::t('clinic', 'Capsules'),
            self::FORM_DROP => Yii::t('clinic', 'Drops'),
            self::FORM_SUPPOSITORY => Yii::t('clinic', 'Suppositories'),
            self::FORM_TOPICAL => Yii::t('clinic', 'Topical'),
            self::FORM_INHALER => Yii::t('clinic', 'Inhaler'),
            self::FORM_SPRAY => Yii::t('clinic', 'Spray'),
            self::FORM_INJECTION_SUBCUTANEOUS => Yii::t('clinic', 'Subcutaneous injection'),
            self::FORM_INJECTION_INTRAMUSCULAR => Yii::t('clinic', 'Intramuscular injection'),
            self::FORM_INJECTION_INTRAVENOUS => Yii::t('clinic', 'Intravenous injection'),
            self::FORM_INJECTION_INTRATHECAL => Yii::t('clinic', 'Intrathecal injection'),
            self::FORM_PATCH => Yii::t('clinic', 'Patches'),
            self::FORM_BUCCAL => Yii::t('clinic', 'Buccal'),
            self::FORM_SUBLINGUAL => Yii::t('clinic', 'Sublingual'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'name', 'formats'], 'required'],
            [['clinic_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['formats', 'forms'], 'safe'],
            [['formats'], 'each', 'rule' => ['integer']],
            [['clinic_id'], 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
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
            'name' => Yii::t('clinic', 'Medicine name'),
            'formats' => Yii::t('clinic', 'Medicine formats'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
            'created_by' => Yii::t('general', 'Created by'),
            'updated_by' => Yii::t('general', 'Updated by'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->name = ucwords(strtolower($this->name));
        $this->forms = Json::encode($this->formats);
        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->formats = Json::decode($this->forms);
    }

    /**
     * Gets query for [[Clinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c');
    }
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->alias('cr');
    }
    
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->alias('up');
    }
}
