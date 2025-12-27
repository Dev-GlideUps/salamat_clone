<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%appointment_id_from_diagnosis}}`.
 */
class m200319_024108_drop_appointment_id_from_diagnosis_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drop foreign key for table `{{%appointment}}`
        $this->dropForeignKey(
            '{{%fk-patient_diagnosis-appointment_id}}',
            '{{%patient_diagnosis}}'
        );

        $this->dropColumn('{{%patient_diagnosis}}', 'appointment_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200319_024108_drop_appointment_id_from_diagnosis_table cannot be reverted.\n";
        return false;
    }
}
