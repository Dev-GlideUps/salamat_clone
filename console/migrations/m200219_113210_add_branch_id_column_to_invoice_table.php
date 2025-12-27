<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%invoice}}`.
 */
class m200219_113210_add_branch_id_column_to_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'branch_id', $this->integer()->notNull());

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-invoice-branch_id}}',
            '{{%invoice}}',
            'branch_id',
            '{{%clinic_branch}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-invoice-branch_id}}',
            '{{%invoice}}'
        );

        $this->dropColumn('{{%invoice}}', 'branch_id');
    }
}
