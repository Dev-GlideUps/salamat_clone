<?php

namespace clinic\models;

use Yii;
use yii\base\Model;

class InvoiceItem extends Model
{
    public $item;
    public $qty;
    public $discount_value;
    public $discount_unit;
    public $amount;
    public $vat;

    public function rules()
    {
        return [
            ['item', 'trim'],
            ['item', 'string', 'min' => 3, 'max' => 255],
            [['item', 'amount'], 'required', 'message' => Yii::t('general', '{attribute} is required')],
            ['qty', 'integer', 'min' => 1],
            ['qty', 'default', 'value' => null],
            ['amount', 'number', 'min' => 0],
            ['discount_value', 'number', 'min' => 0],
            ['discount_value', 'default', 'value' => 0],
            ['discount_unit', 'validateDiscountUnit'],
//            ['discount_unit', 'default', 'value' => null],
            ['vat', 'boolean'],
            ['vat', 'default', 'value' => false],
        ];
    }

    public function validateDiscountUnit($attribute, $params, $validator)
    {
        if (!in_array($this->$attribute, ['fixed', 'percent'])) {
            $this->addError($attribute, 'The discount unit must be either "FIXED" or "PERCENT".');
        }
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'item' => Yii::t('invoice', 'Service / Item'),
            'qty' => Yii::t('invoice', 'Quantity'),
            'discount_value' => Yii::t('invoice', 'Discount Value'),
            'discount_unit' => Yii::t('invoice', 'Discount Unit'),
            'amount' => Yii::t('invoice', 'Amount'),
            'vat' => Yii::t('invoice', 'VAT'),
        ];
    }
}
