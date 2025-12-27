<?php

use console\models\Migration;

/**
 * Class m200319_190234_create_prescription_tables
 */
class m200319_190234_create_prescription_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%prescription}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'diagnosis_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-prescription-patient_id}}',
            '{{%prescription}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%patient_diagnosis}}`
        $this->addForeignKey(
            '{{%fk-prescription-diagnosis_id}}',
            '{{%prescription}}',
            'diagnosis_id',
            '{{%patient_diagnosis}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-prescription-branch_id}}',
            '{{%prescription}}',
            'branch_id',
            '{{%clinic_branch}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%prescription_item}}', [
            'id' => $this->primaryKey(),
            'prescription_id' => $this->integer()->notNull(),
            'medicine' => $this->string()->notNull(),
            'form' => $this->integer()->notNull(),
            'strength' => $this->string(),
            'frequency' => $this->string()->notNull(),
            'duration' => $this->string()->notNull(),
        ], $this->tableOptions);

        // add foreign key for table `{{%prescription}}`
        $this->addForeignKey(
            '{{%fk-prescription_item-prescription_id}}',
            '{{%prescription_item}}',
            'prescription_id',
            '{{%prescription}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%prescription}}`
        $this->dropForeignKey(
            '{{%fk-prescription_item-prescription_id}}',
            '{{%prescription_item}}'
        );

        $this->dropTable('{{%prescription_item}}');

        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-prescription-branch_id}}',
            '{{%prescription}}'
        );

        // drops foreign key for table `{{%patient_diagnosis}}`
        $this->addForeignKey(
            '{{%fk-prescription-diagnosis_id}}',
            '{{%prescription}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-prescription-patient_id}}',
            '{{%prescription}}'
        );

        $this->dropTable('{{%prescription}}');
    }
}
