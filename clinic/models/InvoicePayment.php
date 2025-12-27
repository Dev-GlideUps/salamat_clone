<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%invoice_payment}}".
 *
 * @property int $id
 * @property int $invoice_id
 * @property float $amount_paid
 * @property int $payment_method
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Invoice $invoice
 */
class InvoicePayment extends \yii\db\ActiveRecord
{
    const METHOD_CASH = 0;
    const METHOD_CHEQUE = 1;
    const METHOD_DEBIT_CARD = 2;
    const METHOD_CREDIT_CARD = 3;
    const METHOD_BANK_TRANSFER = 4;
    const METHOD_BENEFIT_PAY = 5;

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
        return '{{%invoice_payment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'amount_paid', 'payment_method'], 'required'],
            [['invoice_id', 'payment_method', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount_paid'], 'number'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('finance', 'Transaction ID'),
            'invoice_id' => Yii::t('finance', 'Invoice ID'),
            'transactionID' => Yii::t('finance', 'Transaction ID'),
            'amount_paid' => Yii::t('finance', 'Payment amount'),
            'payment_method' => Yii::t('finance', 'Payment method'),

            'created_at' => Yii::t('finance', 'Payment date'),
            'updated_at' => Yii::t('general', 'Updated'),
            'created_by' => Yii::t('general', 'Creator'),
            'updated_by' => Yii::t('general', 'Updater'),
        ];
    }

    public static function methodList() {
        return [
            self::METHOD_CASH => Yii::t('finance', 'Cash'),
            self::METHOD_CHEQUE => Yii::t('finance', 'Cheque'),
            self::METHOD_DEBIT_CARD => Yii::t('finance', 'Debit card'),
            self::METHOD_CREDIT_CARD => Yii::t('finance', 'Credit card'),
            self::METHOD_BANK_TRANSFER => Yii::t('finance', 'Bank Transfer'),
            self::METHOD_BENEFIT_PAY => Yii::t('finance', 'Benefit Pay'),
        ];
    }

    public function getTransactionID()
    {
        return str_pad($this->id, 8, "0", STR_PAD_LEFT);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->via('invoice')->alias('p');
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->via('invoice')->alias('b');
    }
}
