<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 */
class m200219_110333_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'appointment_id' => $this->integer(),
            'vat' => $this->float(2),
            'items' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-invoice-patient_id}}',
            '{{%invoice}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%appointment}}`
        $this->addForeignKey(
            '{{%fk-invoice-appointment_id}}',
            '{{%invoice}}',
            'appointment_id',
            '{{%appointment}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%appointment}}`
        $this->dropForeignKey(
            '{{%fk-invoice-appointment_id}}',
            '{{%invoice}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-invoice-patient_id}}',
            '{{%invoice}}'
        );

        $this->dropTable('{{%invoice}}');
    }
}
