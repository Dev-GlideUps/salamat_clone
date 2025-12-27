<?php

use console\models\Migration;

/**
 * Class m190514_082735_create_clinic_tables
 */
class m190514_082735_create_clinic_branch_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%clinic_branch}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string(),
            'address' => $this->string(),
            'location' => $this->string()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_branch-clinic_id}}',
            '{{%clinic_branch}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drop foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-clinic_branch-clinic_id}}',
            '{{%clinic_branch}}'
        );

        $this->dropTable('{{%clinic_branch}}');
    }
}
