<?php

use clinic\models\Appointment;
use clinic\models\Invoice;
use yii\db\Migration;

/**
 * Class m200609_154838_add_invoice_id_to_appointment_table
 */
class m200609_154838_add_invoice_id_to_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%appointment}}', 'invoice_id', $this->integer());

        $this->addForeignKey(
            '{{%fk-appointment-invoice_id}}',
            '{{%appointment}}',
            'invoice_id',
            '{{%invoice}}',
            'id',
            'SET NULL'
        );

        $invoices = Invoice::find()->all();

        foreach ($invoices as $invoice) {
            $appointment = Appointment::findOne($invoice->appointment_id);
            $appointment->updateAttributes(['invoice_id' => $invoice->id]);
        }

        $this->dropForeignKey('{{%fk-invoice-appointment_id}}', '{{%invoice}}');
        $this->dropColumn('{{%invoice}}', 'appointment_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_154838_add_invoice_id_to_appointment_table cannot be reverted.\n";
        return false;
    }
}
