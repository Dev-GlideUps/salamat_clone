<?php

namespace clinic\models\hr;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use clinic\models\Clinic;
use clinic\models\User;

/**
 * This is the model class for table "{{%clinic_employee}}".
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string $cpr
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $cpr_expiry
 * @property string $nationality
 * @property string|null $passport_start
 * @property string|null $passport_expiry
 * @property string|null $visa_expiry
 * @property string|null $residency_start
 * @property string|null $residency_expiry
 * @property string|null $contract_start
 * @property string|null $contract_expiry
 */
class Employee extends \yii\db\ActiveRecord
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
        return '{{%clinic_employee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'name', 'cpr', 'nationality', 'salary'], 'required'],
            [['clinic_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['salary', 'number', 'min' => 0],
            [['cpr_expiry', 'passport_start', 'passport_expiry', 'visa_expiry', 'residency_start', 'residency_expiry', 'contract_start', 'contract_expiry'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'cpr', 'address', 'phone', 'nationality'], 'string', 'max' => 255],
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
            'name' => Yii::t('general', 'Name'),
            'cpr' => Yii::t('general', 'CPR'),
            'address' => Yii::t('general', 'Address'),
            'phone' => Yii::t('general', 'Phone'),
            'cpr_expiry' => Yii::t('hr', 'CPR expiry date'),
            'nationality' => Yii::t('general', 'Nationality'),
            'passport_start' => Yii::t('general', 'Issue date'),
            'passport_expiry' => Yii::t('general', 'Eexpiry date'),
            'visa_expiry' => Yii::t('hr', 'Visa expiry date'),
            'residency_start' => Yii::t('hr', 'Residency start'),
            'residency_expiry' => Yii::t('hr', 'Residency expiry'),
            'contract_start' => Yii::t('general', 'Starting date'),
            'contract_expiry' => Yii::t('general', 'Expiry date'),
            'salary' => Yii::t('hr', 'Salary'),

            'created_by' => Yii::t('general', 'Created by'),
            'updated_by' => Yii::t('general', 'Updated by'),
            'created_at' => Yii::t('general', 'Created at'),
            'updated_at' => Yii::t('general', 'Updated at'),
        ];
    }
    
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c');
    }
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
