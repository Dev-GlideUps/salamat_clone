<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%doctor_clinic_branch}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%doctor}}`
 * - `{{%clinic_branch}}`
 */
class m191209_094812_create_junction_table_for_doctor_and_clinic_branch_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor_clinic_branch}}', [
            'doctor_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'status' => $this->integer(),
            'branch_override' => $this->boolean(),
            'sunday' => $this->string()->null(),
            'monday' => $this->string()->null(),
            'tuesday' => $this->string()->null(),
            'wednesday' => $this->string()->null(),
            'thursday' => $this->string()->null(),
            'friday' => $this->string()->null(),
            'saturday' => $this->string()->null(),
            'PRIMARY KEY(doctor_id, branch_id)',
        ], $this->tableOptions);

        // creates index for column `doctor_id`
        $this->createIndex(
            '{{%idx-doctor_clinic_branch-doctor_id}}',
            '{{%doctor_clinic_branch}}',
            'doctor_id'
        );

        // add foreign key for table `{{%doctor}}`
        $this->addForeignKey(
            '{{%fk-doctor_clinic_branch-doctor_id}}',
            '{{%doctor_clinic_branch}}',
            'doctor_id',
            '{{%doctor}}',
            'id',
            'CASCADE'
        );

        // creates index for column `branch_id`
        $this->createIndex(
            '{{%idx-doctor_clinic_branch-branch_id}}',
            '{{%doctor_clinic_branch}}',
            'branch_id'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-doctor_clinic_branch-branch_id}}',
            '{{%doctor_clinic_branch}}',
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
        // drops foreign key for table `{{%doctor}}`
        $this->dropForeignKey(
            '{{%fk-doctor_clinic_branch-doctor_id}}',
            '{{%doctor_clinic_branch}}'
        );

        // drops index for column `doctor_id`
        $this->dropIndex(
            '{{%idx-doctor_clinic_branch-doctor_id}}',
            '{{%doctor_clinic_branch}}'
        );

        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-doctor_clinic_branch-branch_id}}',
            '{{%doctor_clinic_branch}}'
        );

        // drops index for column `branch_id`
        $this->dropIndex(
            '{{%idx-doctor_clinic_branch-branch_id}}',
            '{{%doctor_clinic_branch}}'
        );

        $this->dropTable('{{%doctor_clinic_branch}}');
    }
}
