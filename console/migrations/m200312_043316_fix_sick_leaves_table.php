<?php

use console\models\Migration;

/**
 * Class m200312_043316_fix_sick_leaves_table
 */
class m200312_043316_fix_sick_leaves_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%patient_sick_leave}}';

        $this->dropForeignKey('{{%fk-patient_sick_leave-appointment_id}}', $table);
        $this->dropColumn($table, 'appointment_id');

        $this->addColumn($table, 'commencing_on', $this->date());
        $this->addColumn($table, 'diagnosis_id', $this->integer());

        // add foreign key for table `{{%diagnosis}}`
        $this->addForeignKey(
            '{{%fk-patient_sick_leave-diagnosis_id}}',
            $table,
            'diagnosis_id',
            '{{%patient_diagnosis}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200312_043316_fix_sick_leaves_table cannot be reverted.\n";

        return false;
    }
}
