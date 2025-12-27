<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%patient}}`.
 */
class m200210_091949_create_patient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%patient}}', [
            'id' => $this->primaryKey(),
            'cpr' => $this->string(12)->notNull(),
            'name' => $this->string()->notNull(),
            'name_alt' => $this->string(),
            'phone' => $this->string(16)->notNull(),
            'gender' => $this->tinyInteger(),
            'height' => $this->smallInteger(),
            'weight' => $this->smallInteger(),
            'photo' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%patient}}');
    }
}
