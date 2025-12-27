<?php

use yii\db\Migration;

/**
 * Class m200803_112633_fix_attachment_path_column
 */
class m200803_112633_fix_attachment_path_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%patient_attachment}}', 'path', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200803_112633_fix_attachment_path_column cannot be reverted.\n";
        return false;
    }
}
