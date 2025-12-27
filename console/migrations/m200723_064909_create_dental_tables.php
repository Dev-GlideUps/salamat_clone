<?php

use console\models\Migration;

/**
 * Class m200723_064909_create_dental_tables
 */
class m200723_064909_create_dental_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dental_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'title_alt' => $this->string(),
            'status' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
        
        $this->createTable('{{%dental_procedure}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
            'description_alt' => $this->string(),
            'code' => $this->string(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%dental_category}}`
        $this->addForeignKey(
            '{{%fk-dental_procedure-category_id}}',
            '{{%dental_procedure}}',
            'category_id',
            '{{%dental_category}}',
            'id',
            'CASCADE'
        );
        
        $this->createTable('{{%dental_record}}', [
            'id' => $this->primaryKey(),
            'procedure_id' => $this->integer()->notNull(),
            'patient_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'notes' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%dental_procedure}}`
        $this->addForeignKey(
            '{{%fk-dental_record-procedure_id}}',
            '{{%dental_record}}',
            'procedure_id',
            '{{%dental_procedure}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%patient}}`
        $this->addForeignKey(
            '{{%fk-dental_record-patient_id}}',
            '{{%dental_record}}',
            'patient_id',
            '{{%patient}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-dental_record-branch_id}}',
            '{{%dental_record}}',
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
        echo "m200723_064909_create_dental_tables cannot be reverted.\n";
        return false;
    }
}
