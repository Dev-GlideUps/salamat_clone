<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%invoice}}`.
 */
class m200220_174603_add_blameable_columns_to_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'created_by', $this->integer());
        $this->addColumn('{{%invoice}}', 'updated_by', $this->integer());
        
        $this->addForeignKey(
            '{{%fk-invoice-created_by}}',
            '{{%invoice}}',
            'created_by',
            '{{%clinic_user}}',
            'id',
            'SET NULL'
        );
        
        $this->addForeignKey(
            '{{%fk-invoice-updated_by}}',
            '{{%invoice}}',
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
            '{{%fk-invoice-updated_by}}',
            '{{%invoice}}'
        );

        $this->dropForeignKey(
            '{{%fk-invoice-created_by}}',
            '{{%invoice}}'
        );

        $this->dropColumn('{{%invoice}}', 'created_by');
        $this->dropColumn('{{%invoice}}', 'updated_by');
    }
}
