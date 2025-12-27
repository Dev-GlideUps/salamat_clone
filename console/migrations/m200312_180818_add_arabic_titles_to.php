<?php

use yii\db\Migration;

/**
 * Class m200312_180818_add_arabic_titles_to
 */
class m200312_180818_add_arabic_titles_to extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'name_alt', $this->string());
        $this->addColumn('{{%clinic_branch}}', 'name_alt', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200312_180818_add_arabic_titles_to cannot be reverted.\n";

        return false;
    }
}
