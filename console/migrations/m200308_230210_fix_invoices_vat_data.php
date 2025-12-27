<?php

use yii\db\Migration;
use clinic\models\Invoice;
use clinic\models\InvoiceItem;

/**
 * Class m200308_230210_fix_invoices_vat_data
 */
class m200308_230210_fix_invoices_vat_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (Invoice::find()->all() as $invoice) {
            $invoiceItems = $invoice->invoiceItems;
            $itemsObj = [];
            foreach ($invoiceItems as $i => $item) {
                if (!isset($item['vat'])) {
                    $invoiceItems[$i]['vat'] = $invoice->vat > 0 ? 1 : 0;
                }
                $itemsObj[$i] = new InvoiceItem($invoiceItems[$i]);
            }
            $invoice->invoiceItems = $itemsObj;
            $invoice->updateAttributes(['items', 'vat']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200308_230210_fix_invoices_vat_data cannot be reverted.\n";

        return false;
    }
}
