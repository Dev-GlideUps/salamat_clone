<?php

use yii\db\Migration;

/**
 * Class m200614_131452_add_vat_number_to_clinic_table
 */
class m200614_131452_add_vat_number_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'vat_account', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200614_131452_add_vat_number_to_clinic_table cannot be reverted.\n";
        return false;
    }
}
