<?php

use yii\db\Migration;

/**
 * Class m200618_150258_add_dark_theme_to_user_profile
 */
class m200618_150258_add_dark_theme_to_user_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic_user}}', 'dark_theme', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200618_150258_add_dark_theme_to_user_profile cannot be reverted.\n";
        return false;
    }
}
