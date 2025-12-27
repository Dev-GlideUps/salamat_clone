<?php

use yii\db\Migration;

/**
 * Class m200310_135859_additional_info_for_patients
 */
class m200310_135859_additional_info_for_patients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%patient}}', 'nationality', $this->string(9));
        $this->addColumn('{{%patient}}', 'emergency_contact', $this->string());
        $this->addColumn('{{%patient}}', 'marital_status', $this->integer());
        $this->addColumn('{{%patient}}', 'blood_type', $this->string(6));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200310_135859_additional_info_for_patients cannot be reverted.\n";

        return false;
    }
}
