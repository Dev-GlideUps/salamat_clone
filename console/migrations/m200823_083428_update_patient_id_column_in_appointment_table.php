<?php

use yii\db\Migration;

/**
 * Class m200823_083428_update_patient_id_column_in_appointment_table
 */
class m200823_083428_update_patient_id_column_in_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%appointment}}', 'patient_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200823_083428_update_patient_id_column_in_appointment_table cannot be reverted.\n";
        return false;
    }
}
