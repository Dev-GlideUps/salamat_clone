<?php

use yii\db\Migration;
use yii\console\controllers\MigrateController;

/**
 * Class m200329_085823_clinic_rbac_init
 */
class m200329_085823_clinic_rbac_init extends Migration
{
    public function init()
    {
        parent::init();
        $authManager = Yii::$app->getAuthManager();
        $authManager->itemTable = '{{%clinic_rbac_item}}';
        $authManager->itemChildTable = '{{%clinic_rbac_item_child}}';
        $authManager->assignmentTable = '{{%clinic_rbac_assignment}}';
        $authManager->ruleTable = '{{%clinic_rbac_rule}}';
    }

    public function safeUp()
    {
        $migration = new MigrateController('migrate', Yii::$app);
        $migration->run('up', ['migrationPath' => '@yii/rbac/migrations']);
        
        $this->delete('{{%migration}}', ['version' => 'm140506_102106_rbac_init']);
        $this->delete('{{%migration}}', ['version' => 'm170907_052038_rbac_add_index_on_auth_assignment_user_id']);
        $this->delete('{{%migration}}', ['version' => 'm180523_151638_rbac_updates_indexes_without_prefix']);
    }

    public function safeDown()
    {
        echo "m200329_085823_clinic_rbac_init cannot be reverted.\n";
        return false;
    }
}
