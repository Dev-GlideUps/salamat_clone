<?php

use yii\db\Migration;

/**
 * Handles adding name_alt to table `{{%doctor}}`.
 */
class m191028_170130_add_name_alt_column_to_doctor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%doctor}}', 'name_alt', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%doctor}}', 'name_alt');
    }
}
