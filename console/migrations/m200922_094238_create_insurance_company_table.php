<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%insurance_company}}`.
 */
class m200922_094238_create_insurance_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%insurance_company}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_alt' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%insurance_company}}');
    }
}
