<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%clinic_favorite_diagnosis}}`.
 */
class m200520_054616_create_clinic_favorite_diagnosis_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clinic_favorite_diagnosis}}', [
            'id' => $this->primaryKey(),
            'clinic_id' => $this->integer()->notNull(),
            'code' => $this->string(),
            'description' => $this->string()->notNull(),
            'notes' => $this->text(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_favorite_diagnosis-clinic_id}}',
            '{{%clinic_favorite_diagnosis}}',
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
        // drops foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-clinic_favorite_diagnosis-clinic_id}}',
            '{{%clinic_favorite_diagnosis}}'
        );

        $this->dropTable('{{%clinic_favorite_diagnosis}}');
    }
}
