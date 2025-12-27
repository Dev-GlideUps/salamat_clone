<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%clinic_employee}}`.
 */
class m200915_133020_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_employee}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'cpr' => $this->string()->notNull(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'cpr_expiry' => $this->date(),
            'nationality' => $this->string()->notNull(),
            'passport_start' => $this->date(),
            'passport_expiry' => $this->date(),
            'visa_expiry' => $this->date(),
            'residency_start' => $this->date(),
            'residency_expiry' => $this->date(),
            'contract_start' => $this->date(),
            'contract_expiry' => $this->date(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_employee-clinic_id}}',
            '{{%clinic_employee}}',
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
        $this->dropForeignKey(
            '{{%fk-clinic_employee-clinic_id}}',
            '{{%clinic_employee}}'
        );

        $this->dropTable('{{%clinic_employee}}');
    }
}
