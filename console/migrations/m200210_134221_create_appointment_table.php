<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%appointment}}`.
 */
class m200210_134221_create_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%appointment}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'doctor_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),

            'status' => $this->tinyInteger(),
            'date' => $this->date()->notNull(),
            'time' => $this->time()->notNull(),
            'duration' => $this->smallInteger(),
            'notes' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-appointment-patient_id}}',
            '{{%appointment}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%doctor}}`
        $this->addForeignKey(
            '{{%fk-appointment-doctor_id}}',
            '{{%appointment}}',
            'doctor_id',
            '{{%doctor}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-appointment-branch_id}}',
            '{{%appointment}}',
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
        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-appointment-patient_id}}',
            '{{%appointment}}'
        );
        
        // drops foreign key for table `{{%doctor}}`
        $this->dropForeignKey(
            '{{%fk-appointment-doctor_id}}',
            '{{%appointment}}'
        );
        
        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-appointment-branch_id}}',
            '{{%appointment}}'
        );

        $this->dropTable('{{%appointment}}');
    }
}
