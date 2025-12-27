<?php

use yii\db\Migration;

/**
 * Class m200728_090918_add_package_info_to_clinic_table
 */
class m200728_090918_add_package_info_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic}}', 'package', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_090918_add_package_info_to_clinic_table cannot be reverted.\n";
        return false;
    }
}
