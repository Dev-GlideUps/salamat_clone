<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%exam_notes}}`.
 */
class m210124_222904_create_exam_notes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_examination_note}}', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'notes' => $this->text()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-patient_examination_note-patient_id}}',
            '{{%patient_examination_note}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-patient_examination_note-branch_id}}',
            '{{%patient_examination_note}}',
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
            '{{%fk-patient_examination_note-branch_id}}',
            '{{%patient_examination_note}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-patient_examination_note-patient_id}}',
            '{{%patient_examination_note}}'
        );

        $this->dropTable('{{%patient_examination_note}}');
    }
}
