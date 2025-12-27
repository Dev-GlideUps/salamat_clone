<?php

use console\models\Migration;

/**
 * Class m191209_094612_clinic_working_hours
 */
class m191209_094612_clinic_working_hours extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_working_hours}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),
            'sunday' => $this->string()->null(),
            'monday' => $this->string()->null(),
            'tuesday' => $this->string()->null(),
            'wednesday' => $this->string()->null(),
            'thursday' => $this->string()->null(),
            'friday' => $this->string()->null(),
            'saturday' => $this->string()->null(),
        ], $this->tableOptions);

         // add foreign key for table `{{%clinic_branch}}`
         $this->addForeignKey(
            '{{%fk-clinic_working_hours-branch_id}}',
            '{{%clinic_working_hours}}',
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
        $this->dropForeignKey(
            '{{%fk-clinic_working_hours-branch_id}}',
            '{{%clinic_working_hours}}'
        );

        $this->dropTable('{{%clinic_working_hours}}');
    }
}
