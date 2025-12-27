<?php

use yii\db\Migration;
use clinic\models\ClinicLink;
use clinic\components\rbac\DbManager;

/**
 * Class m200407_183604_add_admin_roles_to_users
 */
class m200407_183604_add_admin_roles_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = new DbManager();
        $users = ClinicLink::find()->joinWith(['user', 'clinic'])->all();
        
        foreach ($users as $userLink) {
            $item = $authManager->getRole('Admin');
            $authManager->assign($item, $userLink->user_id, $userLink->clinic_id);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200407_183604_add_admin_roles_to_users cannot be reverted.\n";
        return false;
    }
}
