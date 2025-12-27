<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%payment}}`.
 */
class m200223_051621_add_blameable_columns_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice_payment}}', 'created_by', $this->integer());
        $this->addColumn('{{%invoice_payment}}', 'updated_by', $this->integer());
        
        $this->addForeignKey(
            '{{%fk-invoice_payment-created_by}}',
            '{{%invoice_payment}}',
            'created_by',
            '{{%clinic_user}}',
            'id',
            'SET NULL'
        );
        
        $this->addForeignKey(
            '{{%fk-invoice_payment-updated_by}}',
            '{{%invoice_payment}}',
            'updated_by',
            '{{%clinic_user}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-invoice_payment-updated_by}}',
            '{{%invoice_payment}}'
        );

        $this->dropForeignKey(
            '{{%fk-invoice_payment-created_by}}',
            '{{%invoice_payment}}'
        );

        $this->dropColumn('{{%invoice_payment}}', 'created_by');
        $this->dropColumn('{{%invoice_payment}}', 'updated_by');
    }
}
