<?php

use yii\db\Migration;

/**
 * Class m200329_174600_add_sort_column_to_clinic_rbac_items
 */
class m200329_174600_add_sort_column_to_clinic_rbac_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic_rbac_item}}', 'sort', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200329_174600_add_sort_column_to_clinic_rbac_items cannot be reverted.\n";

        return false;
    }
}
