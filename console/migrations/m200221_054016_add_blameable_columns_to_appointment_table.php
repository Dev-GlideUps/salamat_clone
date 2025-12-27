<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%appointment}}`.
 */
class m200221_054016_add_blameable_columns_to_appointment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%appointment}}', 'created_by', $this->integer());
        $this->addColumn('{{%appointment}}', 'updated_by', $this->integer());
        
        $this->addForeignKey(
            '{{%fk-appointment-created_by}}',
            '{{%appointment}}',
            'created_by',
            '{{%clinic_user}}',
            'id',
            'SET NULL'
        );
        
        $this->addForeignKey(
            '{{%fk-appointment-updated_by}}',
            '{{%appointment}}',
            'updated_by',
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
        $this->dropForeignKey(
            '{{%fk-appointment-updated_by}}',
            '{{%appointment}}'
        );

        $this->dropForeignKey(
            '{{%fk-appointment-created_by}}',
            '{{%appointment}}'
        );

        $this->dropColumn('{{%appointment}}', 'created_by');
        $this->dropColumn('{{%appointment}}', 'updated_by');
    }
}
