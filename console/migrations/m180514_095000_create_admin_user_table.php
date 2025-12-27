<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%admin_user}}`.
 */
class m180514_095000_create_admin_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%admin_user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'access_token' => $this->string(64)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),

            'registration_ip' => $this->string()->notNull(),
            'password_updated_at' => $this->integer(),
            'last_login_at' => $this->integer(),
            'blocked_at' => $this->integer(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_user}}');
    }
}
