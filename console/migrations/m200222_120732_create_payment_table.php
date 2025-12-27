<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m200222_120732_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_payment}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'amount_paid' => $this->float(3)->notNull(),
            'payment_method' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%invoice}}`
        $this->addForeignKey(
            '{{%fk-invoice_payment-invoice_id}}',
            '{{%invoice_payment}}',
            'invoice_id',
            '{{%invoice}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%invoice}}`
        $this->dropForeignKey(
            '{{%fk-invoice_payment-invoice_id}}',
            '{{%invoice_payment}}'
        );

        $this->dropTable('{{%invoice_payment}}');
    }
}
