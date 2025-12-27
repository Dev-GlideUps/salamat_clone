<?php

use console\models\Migration;

/**
 * Class m200212_111805_create_services_tables
 */
class m200212_111805_create_services_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%branch_service}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),

            'title' => $this->string()->notNull(),
            'title_alt' => $this->string(),
            'duration' => $this->integer()->notNull(),
            'price' => $this->float(3),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-branch_service-branch_id}}',
            '{{%branch_service}}',
            'branch_id',
            '{{%clinic_branch}}',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%doctor_service}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),
            'doctor_id' => $this->integer()->notNull(),

            'title' => $this->string()->notNull(),
            'title_alt' => $this->string(),
            'duration' => $this->integer()->notNull(),
            'price' => $this->float(3),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%doctor}}`
        $this->addForeignKey(
            '{{%fk-doctor_service-doctor_id}}',
            '{{%doctor_service}}',
            'doctor_id',
            '{{%doctor}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-doctor_service-branch_id}}',
            '{{%doctor_service}}',
            'branch_id',
            '{{%clinic_branch}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-doctor_service-branch_id}}',
            '{{%doctor_service}}'
        );

        $this->dropTable('{{%doctor_service}}');

        // drops foreign key for table `{{%clinic_branch}}`
        $this->dropForeignKey(
            '{{%fk-branch_service-branch_id}}',
            '{{%branch_service}}'
        );

        $this->dropTable('{{%branch_service}}');
    }
}
