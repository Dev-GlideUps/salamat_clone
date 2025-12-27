<?php

use yii\db\Migration;

/**
 * Class m200214_204316_fix_appointment_table_columns
 */
class m200214_204316_fix_appointment_table_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%appointment}}', 'end_time', $this->time()->notNull());
        $this->addColumn('{{%appointment}}', 'price', $this->float(3));
        $this->addColumn('{{%appointment}}', 'service', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%appointment}}', 'end_time');
        $this->dropColumn('{{%appointment}}', 'price');
        $this->dropColumn('{{%appointment}}', 'service');
    }
}
