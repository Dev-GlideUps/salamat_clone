<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%clinic_assignment}}`.
 */
class m200402_084342_add_clinic_id_column_to_clinic_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clinic_rbac_assignment}}', 'clinic_id', $this->integer());
        $this->alterColumn('{{%clinic_rbac_assignment}}', 'user_id', $this->integer());
        $this->dropForeignKey('{{%clinic_rbac_assignment_ibfk_1}}', '{{%clinic_rbac_assignment}}');
        $this->dropPrimaryKey('PRIMARY', '{{%clinic_rbac_assignment}}');
        $this->dropIndex('{{%idx-auth_assignment-user_id}}', '{{%clinic_rbac_assignment}}');

        $this->addPrimaryKey('', '{{%clinic_rbac_assignment}}', [
            'item_name',
            'user_id',
            'clinic_id',
        ]);

        // add foreign key for table `{{%clinic_rbac_item}}`
        $this->addForeignKey(
            '{{%fk-clinic_rbac_assignment-item_name}}',
            '{{%clinic_rbac_assignment}}',
            'item_name',
            '{{%clinic_rbac_item}}',
            'name',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic_user}}`
        $this->addForeignKey(
            '{{%fk-clinic_rbac_assignment-user_id}}',
            '{{%clinic_rbac_assignment}}',
            'user_id',
            '{{%clinic_user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%clinic}}`
        $this->addForeignKey(
            '{{%fk-clinic_rbac_assignment-clinic_id}}',
            '{{%clinic_rbac_assignment}}',
            'clinic_id',
            '{{%clinic}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200402_084342_add_clinic_id_column_to_clinic_assignment_table cannot be reverted.\n";
        return false;
    }
}
