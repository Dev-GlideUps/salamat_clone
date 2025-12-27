<?php

use yii\db\Migration;

/**
 * Class m200730_120743_add_branch_id_to_attachment_table
 */
class m200730_120743_add_branch_id_to_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drop foreign key for table `{{%clinic}}`
        $this->dropForeignKey(
            '{{%fk-patient_attachment-clinic_id}}',
            '{{%patient_attachment}}'
        );
        $this->dropColumn('{{%patient_attachment}}', 'clinic_id');
        $this->addColumn('{{%patient_attachment}}', 'branch_id', $this->integer()->notNull());
        // add foreign key for table `{{%clinic_branch}}`
        $this->addForeignKey(
            '{{%fk-patient_attachment-branch_id}}',
            '{{%patient_attachment}}',
            'branch_id',
            '{{%clinic_branch}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200730_120743_add_branch_id_to_attachment_table cannot be reverted.\n";
        return false;
    }
}
