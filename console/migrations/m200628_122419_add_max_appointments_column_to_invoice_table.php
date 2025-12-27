<?php

use yii\db\Migration;
use clinic\models\Invoice;

/**
 * Handles adding columns to table `{{%invoice}}`.
 */
class m200628_122419_add_max_appointments_column_to_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'max_appointments', $this->integer()->notNull());

        foreach (Invoice::find()->joinWith('appointments')->all() as $item) {
            $item->updateAttributes(['max_appointments' => count($item->appointments)]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200628_122419_add_max_appointments_column_to_invoice_table cannot be reverted.\n";
        return false;
    }
}
