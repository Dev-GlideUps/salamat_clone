<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%appointment_sms}}`.
 */
class m210422_094605_add_response_column_to_appointment_sms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic_appointment_sms}}', 'response', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%clinic_appointment_sms}}', 'response');
    }
}
