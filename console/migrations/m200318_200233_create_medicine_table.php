<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%medicine}}`.
 */
class m200318_200233_create_medicine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_medicine}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'forms' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_medicine-clinic_id}}',
            '{{%clinic_medicine}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-clinic_medicine-clinic_id}}',
            '{{%clinic_medicine}}'
        );

        $this->dropTable('{{%medicine}}');
    }
}
