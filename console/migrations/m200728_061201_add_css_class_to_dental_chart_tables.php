<?php

use yii\db\Migration;

/**
 * Class m200728_061201_add_css_class_to_dental_chart_tables
 */
class m200728_061201_add_css_class_to_dental_chart_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dental_category}}', 'chart_class', $this->string()->notNull());
        $this->addColumn('{{%dental_procedure}}', 'chart_class', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_061201_add_css_class_to_dental_chart_tables cannot be reverted.\n";
        return false;
    }
}
