<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%patient_clinic_relation}}`.
 */
class m200218_080520_create_patient_clinic_relation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient_clinic_relation}}', [
            'clinic_id' => $this->integer()->notNull(),
            'patient_id' => $this->integer()->notNull(),
            'profile_ref' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY(clinic_id, patient_id)',
        ], $this->tableOptions);

        // creates index for column `clinic_id`
        $this->createIndex(
            '{{%idx-patient_clinic_relation-clinic_id}}',
            '{{%patient_clinic_relation}}',
            'clinic_id'
        );

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-patient_clinic_relation-clinic_id}}',
            '{{%patient_clinic_relation}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );

        // creates index for column `patient_id`
        $this->createIndex(
            '{{%idx-patient_clinic_relation-patient_id}}',
            '{{%patient_clinic_relation}}',
            'patient_id'
        );

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-patient_clinic_relation-patient_id}}',
            '{{%patient_clinic_relation}}',
            'patient_id',
            '{{%patient}}',
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
            '{{%fk-patient_clinic_relation-patient_id}}',
            '{{%patient_clinic_relation}}'
        );

        // drops index for column `patient_id`
        $this->dropIndex(
            '{{%idx-patient_clinic_relation-patient_id}}',
            '{{%patient_clinic_relation}}'
        );

        // drops foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-patient_clinic_relation-clinic_id}}',
            '{{%patient_clinic_relation}}'
        );

        // drops index for column `clinic_id`
        $this->dropIndex(
            '{{%idx-patient_clinic_relation-clinic_id}}',
            '{{%patient_clinic_relation}}'
        );

        $this->dropTable('{{%patient_clinic_relation}}');
    }
}
