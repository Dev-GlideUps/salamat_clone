<?php

use yii\db\Migration;

/**
 * Class m200626_165745_add_max_appointments_to_services
 */
class m200626_165745_add_max_appointments_to_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%branch_service}}', 'max_appointments', $this->integer());
        $this->addColumn('{{%doctor_service}}', 'max_appointments', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200626_165745_add_max_appointments_to_services cannot be reverted.\n";
        return false;
    }
}
