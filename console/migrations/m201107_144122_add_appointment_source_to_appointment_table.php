<?php

use yii\db\Migration;

/**
 * Class m201107_144122_add_appointment_source_to_appointment_table
 */
class m201107_144122_add_appointment_source_to_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%appointment}}', 'source', $this->tinyInteger()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201107_144122_add_appointment_source_to_appointment_table cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201107_144122_add_appointment_source_to_appointment_table cannot be reverted.\n";

        return false;
    }
    */
}
