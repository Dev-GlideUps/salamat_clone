<?php

use yii\db\Migration;

/**
 * Class m200915_131513_add_hr_permissions
 */
class m200915_131513_add_hr_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new \clinic\components\rbac\DbManager();

        $roles = new \stdClass();
        $roles->admin = $authManager->getRole('Admin');
        $roles->hr = $authManager->createRole('HR');
        $authManager->add($roles->hr);

        $manage = $authManager->createPermission('Manage employees');
        $manage->description = 'Manage employees (full access)';
        $manage->sort = 34;
        $authManager->add($manage);
        $authManager->addChild($roles->admin, $manage);
        $authManager->addChild($roles->hr, $manage);

        $item = $authManager->createPermission('Create employees');
        $item->description = 'Create employees';
        $item->sort = 35;
        $authManager->add($item);
        $authManager->addChild($manage, $item);

        $item = $authManager->createPermission('Update employees');
        $item->description = 'Update employees';
        $item->sort = 36;
        $authManager->add($item);
        $authManager->addChild($manage, $item);

        $item = $authManager->createPermission('Delete employees');
        $item->description = 'Delete employees';
        $item->sort = 37;
        $authManager->add($item);
        $authManager->addChild($manage, $item);

        $item = $authManager->createPermission('View employees');
        $item->description = 'View employees';
        $item->sort = 38;
        $authManager->add($item);
        $authManager->addChild($manage, $item);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200915_131513_add_hr_permissions cannot be reverted.\n";
        return false;
    }
}
