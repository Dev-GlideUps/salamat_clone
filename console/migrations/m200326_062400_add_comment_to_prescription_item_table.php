<?php

use yii\db\Migration;

/**
 * Class m200326_062400_add_comment_to_prescription_item_table
 */
class m200326_062400_add_comment_to_prescription_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%prescription_item}}', 'comment', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%prescription_item}}', 'comment');
    }
}
