<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%clinic_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%clinic}}`
 * - `{{%clinic_user}}`
 */
class m190506_153931_create_junction_table_for_clinic_and_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_user_relation}}', [
            'clinic_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'PRIMARY KEY(clinic_id, user_id)',
        ], $this->tableOptions);

        // creates index for column `clinic_id`
        $this->createIndex(
            '{{%idx-clinic_user_relation-clinic_id}}',
            '{{%clinic_user_relation}}',
            'clinic_id'
        );

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_user_relation-clinic_id}}',
            '{{%clinic_user_relation}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-clinic_user_relation-user_id}}',
            '{{%clinic_user_relation}}',
            'user_id'
        );

        // add foreign key for table `{{%clinic_user}}`
        $this->addForeignKey(
            '{{%fk-clinic_user_relation-user_id}}',
            '{{%clinic_user_relation}}',
            'user_id',
            '{{%clinic_user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_user-active_clinic}}',
            '{{%clinic_user}}',
            'active_clinic',
            '{{%clinic}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-clinic_user_relation-clinic_id}}',
            '{{%clinic_user_relation}}'
        );

        // drops index for column `clinic_id`
        $this->dropIndex(
            '{{%idx-clinic_user_relation-clinic_id}}',
            '{{%clinic_user_relation}}'
        );

        // drops foreign key for table `{{%clinic_user}}`
        $this->dropForeignKey(
            '{{%fk-clinic_user_relation-user_id}}',
            '{{%clinic_user_relation}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-clinic_user_relation-user_id}}',
            '{{%clinic_user_relation}}'
        );

        $this->dropTable('{{%clinic_user_relation}}');

        // drops foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-clinic_user-active_clinic}}',
            '{{%clinic_user}}'
        );
    }
}
