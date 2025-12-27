<?php

use yii\db\Migration;

/**
 * Class m200529_142555_add_timestamp_columns_to_appointments_states
 */
class m200529_142555_add_timestamp_columns_to_appointments_states extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%appointment}}', 'confirmed_at', $this->integer());
        $this->addColumn('{{%appointment}}', 'check_in_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200529_142555_add_timestamp_columns_to_appointments_states cannot be reverted.\n";

        return false;
    }
}
