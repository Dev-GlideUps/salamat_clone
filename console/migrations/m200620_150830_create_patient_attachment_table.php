<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%patient_attachment}}`.
 */
class m200620_150830_create_patient_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_attachment}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'patient_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'path' => $this->integer()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        $this->createTable('{{%patient_attachment_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'title_alt' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-patient_attachment-clinic_id}}',
            '{{%patient_attachment}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-patient_attachment-patient_id}}',
            '{{%patient_attachment}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%patient_attachment_category}}`
        $this->addForeignKey(
            '{{%fk-patient_attachment-category_id}}',
            '{{%patient_attachment}}',
            'category_id',
            '{{%patient_attachment_category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%patient_attachment_category}}`
        $this->dropForeignKey(
            '{{%fk-patient_attachment-category_id}}',
            '{{%patient_attachment}}'
        );

        // drops foreign key for table `{{%patient}}`
        $this->dropForeignKey(
            '{{%fk-patient_attachment-patient_id}}',
            '{{%patient_attachment}}'
        );

        // drops foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-patient_attachment-clinic_id}}',
            '{{%patient_attachment}}'
        );

        $this->dropTable('{{%patient_attachment_category}}');

        $this->dropTable('{{%patient_attachment}}');
    }
}
