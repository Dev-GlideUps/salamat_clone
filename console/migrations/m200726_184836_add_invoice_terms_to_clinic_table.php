<?php

use yii\db\Migration;

/**
 * Class m200726_184836_add_invoice_terms_to_clinic_table
 */
class m200726_184836_add_invoice_terms_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'invoice_terms', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200726_184836_add_invoice_terms_to_clinic_table cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200726_184836_add_invoice_terms_to_clinic_table cannot be reverted.\n";

        return false;
    }
    */
}
