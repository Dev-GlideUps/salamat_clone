<?php

use yii\db\Migration;

/**
 * Class m200609_180127_add_accountant_role
 */
class m200609_180127_add_accountant_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new \clinic\components\rbac\DbManager();

        $role = $authManager->createRole('Accountant');
        $authManager->add($role);

        $authManager->addChild($role, $authManager->getPermission('View invoices'));
        $authManager->addChild($role, $authManager->getPermission('View payments'));
        $authManager->addChild($role, $authManager->getPermission('View analytics'));
        $authManager->addChild($role, $authManager->getPermission('View reports'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_180127_add_accountant_role cannot be reverted.\n";
        return false;
    }
}
