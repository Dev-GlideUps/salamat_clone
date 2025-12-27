<?php

use yii\db\Migration;
use clinic\models\dental\Record;

/**
 * Class m200730_084109_add_date_to_dental_record_table
 */
class m200730_084109_add_date_to_dental_record_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dental_record}}', 'procedure_date', $this->date());

        $records = Record::find()->all();
        foreach ($records as $item) {
            $item->updateAttributes(['procedure_date' => date('Y-m-d', $item->created_at)]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200730_084109_add_date_to_dental_record_table cannot be reverted.\n";
        return false;
    }
}
