<?php

use yii\db\Migration;

/**
 * Class m200223_042405_add_payed_amount_to_invoice
 */
class m200223_042405_add_payed_amount_to_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'paid', $this->float(3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%invoice}}', 'paid');
    }
}
