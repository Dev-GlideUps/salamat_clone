<?php

use yii\db\Migration;

/**
 * Class m200403_101601_move_blocked_at_column_to_user_clinic_link_table
 */
class m200403_101601_move_blocked_at_column_to_user_clinic_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%clinic_user}}', 'blocked_at');
        $this->addColumn('{{%clinic_user_relation}}', 'blocked_at', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%clinic_user_relation}}', 'blocked_at');
        $this->addColumn('{{%clinic_user}}', 'blocked_at', $this->integer()->null());
    }
}
