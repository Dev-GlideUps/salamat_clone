<?php

use yii\db\Migration;

/**
 * Class m191020_123436_alter_exp_column_in_doctor_table
 */
class m191020_123436_alter_exp_column_in_doctor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%doctor}}', 'experience', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%doctor}}', 'experience', $this->integer());
    }
}
