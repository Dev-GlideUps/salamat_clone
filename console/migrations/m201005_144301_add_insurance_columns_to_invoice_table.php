<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%invoice}}`.
 */
class m201005_144301_add_insurance_columns_to_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'has_insurance', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{%invoice}}', 'insurance_seller', $this->integer());
        $this->addColumn('{{%invoice}}', 'insurance_buyer', $this->string());
        $this->addColumn('{{%invoice}}', 'insurance_amount', $this->float());
        $this->addColumn('{{%invoice}}', 'insurance_coverage', $this->float());
        $this->addColumn('{{%invoice}}', 'insurance_mode', $this->integer());

        // add foreign key for table `{{%insurance_company}}`
        $this->addForeignKey(
            '{{%fk-invoice-insurance_seller}}',
            '{{%invoice}}',
            'insurance_seller',
            '{{%insurance_company}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `{{%insurance_company}}`
        $this->dropForeignKey(
            '{{%fk-invoice-insurance_seller}}',
            '{{%invoice}}'
        );

        $this->dropColumn('{{%invoice}}', 'insurance_mode');
        $this->dropColumn('{{%invoice}}', 'insurance_coverage');
        $this->dropColumn('{{%invoice}}', 'insurance_amount');
        $this->dropColumn('{{%invoice}}', 'insurance_buyer');
        $this->dropColumn('{{%invoice}}', 'insurance_seller');
        $this->dropColumn('{{%invoice}}', 'has_insurance');
    }
}
