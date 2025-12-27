<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%sms_notifications}}`.
 */
class m210418_223345_create_sms_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_appointment_sms}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'appointment_id' => $this->integer()->notNull(),
            'mobile' => $this->string()->notNull(),
            'message' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'send_at' => $this->bigInteger()->notNull(),
            'created_at' => $this->bigInteger()->notNull(),
            'updated_at' => $this->bigInteger()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey(
            '{{%fk-clinic_appointment_sms-clinic_id}}',
            '{{%clinic_appointment_sms}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-clinic_appointment_sms-appointment_id}}',
            '{{%clinic_appointment_sms}}',
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
        $this->dropForeignKey(
            '{{%fk-clinic_appointment_sms-appointment_id}}',
            '{{%clinic_appointment_sms}}'
        );

        $this->dropForeignKey(
            '{{%fk-clinic_appointment_sms-clinic_id}}',
            '{{%clinic_appointment_sms}}'
        );

        $this->dropTable('{{%clinic_appointment_sms}}');
    }
}
