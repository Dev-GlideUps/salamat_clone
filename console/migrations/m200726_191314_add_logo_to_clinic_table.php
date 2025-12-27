<?php

use yii\db\Migration;

/**
 * Class m200726_191314_add_logo_to_clinic_table
 */
class m200726_191314_add_logo_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'logo', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200726_191314_add_logo_to_clinic_table cannot be reverted.\n";
        return false;
    }
}
