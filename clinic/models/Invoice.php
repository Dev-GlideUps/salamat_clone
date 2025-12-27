<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;
use insurance\models\Company;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property int $id
 * @property int $patient_id
 * @property float|null $vat
 * @property float|null $discount #46
 * @property string|null $items
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Appointment $appointment
 * @property Patient $patient
 */
class Invoice extends \yii\db\ActiveRecord
{
    const VAT_PERCENTAGE = 0.1; // 10%

    const STATUS_CANCELED = 0;
    const STATUS_ACTIVE = 1;

    const INSURANCE_PERCENT = 0;
    const INSURANCE_FIXED = 1;

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
        return '{{%invoice}}';
    }

    public function getInvoiceID()
    {
        return str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'patient_id', 'total', 'vat', 'max_appointments'], 'required'],
            [['branch_id', 'patient_id', 'status', 'insurance_seller', 'insurance_mode', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['has_insurance', 'insurance_claimed'], 'boolean'],
            [['insurance_mode'], 'validateInsuranceUnit'],
            [['insurance_buyer'], 'string', 'max' => 255],
            [['vat', 'discount', 'total', 'paid', 'insurance_amount', 'insurance_coverage'], 'number', 'min' => 0],
            [['paid', 'vat', 'discount', 'has_insurance', 'insurance_amount', 'insurance_coverage'], 'default', 'value' => 0],
            [['items'], 'safe'],
            [['max_appointments'], 'integer', 'min' => 1],
            [['branch_id'], 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['insurance_seller'], 'exist', 'targetClass' => Company::className(), 'targetAttribute' => ['insurance_seller' => 'id'], 'skipOnEmpty' => true],
            [['insurance_seller', 'insurance_buyer', 'insurance_amount', 'insurance_mode'], 'required',
            'when' => function ($model) {
                return $model->has_insurance == true;
            }, 'whenClient' => "function (attribute, value) {
                return $('#invoice-has_insurance').prop('checked') == true;
            }"],
        ];
    }

    public function validateInsuranceUnit($attribute, $params, $validator)
    {
        if (!in_array($this->$attribute, [self::INSURANCE_PERCENT, self::INSURANCE_FIXED])) {
            $this->addError($attribute, 'The discount unit must be either "FIXED" or "PERCENT".');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('finance', 'Invoice ID'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'patient_id' => Yii::t('patient', 'Patient'),
            'invoiceID' => Yii::t('finance', 'Invoice ID'),
            'vat' => Yii::t('finance', 'VAT'),
            'discount' => Yii::t('finance', 'Discount'),
            'items' => Yii::t('finance', 'Invoice services / items'),
            'total' => Yii::t('finance', 'Total amount'),
            'paid' => Yii::t('finance', 'Amount paid'),
            'balance' => Yii::t('finance', 'Balance due'),
            'max_appointments' => Yii::t('general', 'Number of appointments'),
            'has_insurance' => Yii::t('insurance', 'Insurance'),
            'insurance_coverage' => Yii::t('insurance', 'Insurance'),
            'insurance_seller' => Yii::t('insurance', 'Insurance seller'),
            'insurance_buyer' => Yii::t('insurance', 'Insurance buyer'),
            'insurance_amount' => Yii::t('finance', 'Amount'),
            'insurance_mode' => Yii::t('insurance', 'Insurance mode'),
            'insurance_claimed' => Yii::t('insurance', 'Insurance claimed'),

            'created_at' => Yii::t('finance', 'Date of issue'),
            'updated_at' => Yii::t('general', 'Updated'),
            'created_by' => Yii::t('general', 'Creator'),
            'updated_by' => Yii::t('general', 'Updater'),
        ];
    }
    
    public function setInvoiceItems($items)
    {
        $this->total = 0;
        $this->vat = 0;
        $this->discount = 0;
        $this->insurance_coverage = 0;
        $itemsArray = [];
        foreach ($items as $item) {
            $qty = $item->qty === null ? 1 : $item->qty;
            $subtotal = $qty * $item->amount;
            $discount = 0;
            if (!empty($item->discount_unit)) {
                if ($item->discount_unit == 'percent') {
                    $discount = ($subtotal * ($item->discount_value / 100));
                } else {
                    $discount = $item->discount_value;
                }
                $this->discount += $discount;
            }
            $subtotal -= $discount;
            if ($item->vat == true) {
                $this->vat += ($subtotal * self::VAT_PERCENTAGE);
                $subtotal *= (self::VAT_PERCENTAGE + 1.0);
            }
            $this->total += $subtotal;
            $itemsArray[] = $item->attributes;
        }

        if ($this->has_insurance) {
            switch ($this->insurance_mode) {
                case self::INSURANCE_PERCENT: $this->insurance_coverage = $this->total * ($this->insurance_amount / 100); break;
                case self::INSURANCE_FIXED: $this->insurance_coverage = $this->insurance_amount; break;
            }
        } else {
            $this->insurance_seller = NULL;
            $this->insurance_buyer = NULL;
            $this->insurance_amount = NULL;
            $this->insurance_coverage = NULL;
            $this->insurance_mode = NULL;
        }

        $this->total -= $this->insurance_coverage;

        $this->items = Json::encode($itemsArray);
    }
    
    public function getBalance()
    {
        return $this->total - $this->paid;
    }
    
    public function getCanUpdateInvoice()
    {
        return (Yii::$app->user->can('Admin') && time() <= strtotime('+1 day', $this->created_at));
    }
    
    public function getCanAddAppointment()
    {
        return ($this->max_appointments > count($this->appointments));
    }
    
    public function getInvoiceItems()
    {
        return Json::decode($this->items);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->alias('p');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->alias('b');
    }

    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c')->via('branch');
    }

    public function getInsuranceSeller()
    {
        return $this->hasOne(Company::className(), ['id' => 'insurance_seller'])->alias('ins');
    }

    public function getPayments()
    {
        return $this->hasMany(InvoicePayment::className(), ['invoice_id' => 'id'])->alias('ip');
    }
    

    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['invoice_id' => 'id'])->alias('app')->orderBy(['app.date' => SORT_ASC]);
    }
}
