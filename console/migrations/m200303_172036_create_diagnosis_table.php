<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%diagnosis}}`.
 */
class m200303_172036_create_diagnosis_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_diagnosis}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'code' => $this->string(),
            'description' => $this->string()->notNull(),
            'notes' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-patient_diagnosis-patient_id}}',
            '{{%patient_diagnosis}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-patient_diagnosis-branch_id}}',
            '{{%patient_diagnosis}}',
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
        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-patient_diagnosis-branch_id}}',
            '{{%patient_diagnosis}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-patient_diagnosis-patient_id}}',
            '{{%patient_diagnosis}}'
        );

        $this->dropTable('{{%patient_diagnosis}}');
    }
}
