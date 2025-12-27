<?php

use yii\db\Migration;

/**
 * Class m200604_063132_add_branch_appointments_control_fields
 */
class m200604_063132_add_branch_appointments_control_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic_branch}}', 'schedule_starting', $this->time()->defaultValue('00:00:00'));
        $this->addColumn('{{%clinic_branch}}', 'schedule_ending', $this->time()->defaultValue('00:00:00'));
        $this->addColumn('{{%clinic_branch}}', 'auto_closing', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200604_063132_add_branch_appointments_control_fields cannot be reverted.\n";
        return false;
    }
}
