<?php

use yii\db\Migration;

/**
 * Class m200620_155603_add_patient_attachment_permissions
 */
class m200620_155603_add_patient_attachment_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new \clinic\components\rbac\DbManager();

        $roles = [
            'admin' => $authManager->getRole('Admin'),
            'doctor' => $authManager->getRole('Doctor'),
            'staff' => $authManager->getRole('Medical staff'),
        ];

        $manage = $authManager->createPermission('Manage patient attachments');
        $manage->description = 'Manage patient attachments (full access)';
        $manage->sort = 25;
        $authManager->add($manage);
        $authManager->addChild($roles['admin'], $manage);

        $item = $authManager->createPermission('View patient attachments');
        $item->description = 'View patient attachments';
        $item->sort = 25;
        $authManager->add($item);
        $authManager->addChild($roles['staff'], $item);
        $authManager->addChild($roles['doctor'], $item);
        $authManager->addChild($manage, $item);

        $item = $authManager->createPermission('Create patient attachments');
        $item->description = 'Create patient attachments';
        $item->sort = 25;
        $authManager->add($item);
        $authManager->addChild($roles['staff'], $item);
        $authManager->addChild($roles['doctor'], $item);
        $authManager->addChild($manage, $item);

        $item = $authManager->createPermission('Update patient attachments');
        $item->description = 'Update patient attachments';
        $item->sort = 25;
        $authManager->add($item);
        $authManager->addChild($manage, $item);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200620_155603_add_patient_attachment_permissions cannot be reverted.\n";
        return false;
    }
}
