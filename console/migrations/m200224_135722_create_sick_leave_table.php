<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%sick_leave}}`.
 */
class m200224_135722_create_sick_leave_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_sick_leave}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'appointment_id' => $this->integer()->notNull(),
            'leave_type' => $this->integer()->notNull(),
            'advise' => $this->integer()->notNull(),
            'days' => $this->integer()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-patient_sick_leave-patient_id}}',
            '{{%patient_sick_leave}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%appointment}}`
        $this->addForeignKey(
            '{{%fk-patient_sick_leave-appointment_id}}',
            '{{%patient_sick_leave}}',
            'appointment_id',
            '{{%appointment}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%appointment}}`
        $this->dropForeignKey(
            '{{%fk-patient_sick_leave-appointment_id}}',
            '{{%patient_sick_leave}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-patient_sick_leave-patient_id}}',
            '{{%patient_sick_leave}}'
        );

        $this->dropTable('{{%patient_sick_leave}}');
    }
}
