<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%clinic}}`.
 */
class m190506_151535_create_clinic_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%clinic}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%clinic}}');
    }
}
