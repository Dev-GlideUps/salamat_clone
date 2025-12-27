<?php

use yii\db\Migration;

/**
 * Class m200417_224129_add_report_permissions
 */
class m200417_224129_add_report_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new \clinic\components\rbac\DbManager();

        $admin = $authManager->getRole('Admin');

        $item = $authManager->createPermission('View analytics');
        $item->description = 'View analytics';
        $item->sort = 32;
        $authManager->add($item);
        $authManager->addChild($admin, $item);

        $item = $authManager->createPermission('View reports');
        $item->description = 'View reports';
        $item->sort = 33;
        $authManager->add($item);
        $authManager->addChild($admin, $item);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200417_224129_add_report_permissions cannot be reverted.\n";
        return false;
    }
}
