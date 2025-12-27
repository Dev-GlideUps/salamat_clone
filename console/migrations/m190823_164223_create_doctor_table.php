<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%doctor}}`.
 */
class m190823_164223_create_doctor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%doctor}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'speciality' => $this->integer()->null(),
            'description' => $this->text(),
            'experience' => $this->integer(),
            'mobile' => $this->string(),
            'language' => $this->string()->notNull(),
            'photo' => $this->string(),
            'user_id' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic_user}}`
        $this->addForeignKey(
            '{{%fk-doctor-user_id}}',
            '{{%doctor}}',
            'user_id',
            '{{%clinic_user}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // drop foreign key for table `{{%clinic_user}}`
        $this->dropForeignKey(
            '{{%fk-doctor-user_id}}',
            '{{%doctor}}'
        );

        $this->dropTable('{{%doctor}}');
    }
}
