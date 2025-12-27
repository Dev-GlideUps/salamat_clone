<?php

use yii\db\Migration;

/**
 * Class m200728_104002_add_tooth_number_to_dental_record_table
 */
class m200728_104002_add_tooth_number_to_dental_record_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dental_record}}', 'teeth', $this->integer()->notNUll());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_104002_add_tooth_number_to_dental_record_table cannot be reverted.\n";
        return false;
    }
}
