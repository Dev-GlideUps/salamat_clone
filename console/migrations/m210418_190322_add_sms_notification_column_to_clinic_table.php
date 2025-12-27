<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%clinic}}`.
 */
class m210418_190322_add_sms_notification_column_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'appointment_sms', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%clinic}}', 'appointment_sms');
    }
}
