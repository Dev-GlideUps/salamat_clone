<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%diagnosis_code}}`.
 */
class m200228_135831_create_diagnosis_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%diagnosis_code}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        $stream = fopen(Yii::getAlias('@console/models/icd10cm_codes_2020.txt'), 'r');
        $rows = [];
        $count = 0;
        $insertCount = 0;
        while (($line = fgets($stream)) !== false) {
            $code = substr_replace(trim(substr($line, 0, 7)), '.', 3, 0);
            $description = trim(substr($line, 6));
            $rows[] = [$code, $description, time(), time()];
            $insertCount++;
            $count++;

            if ($count == 4999) {
                Yii::$app->db->createCommand()->batchInsert('{{%diagnosis_code}}', ['code', 'description', 'created_at', 'updated_at'], $rows)->execute();
                $rows = [];
                $count = 0;
            }
        }
        fclose($stream);

        if ($count > 0) {
            Yii::$app->db->createCommand()->batchInsert('{{%diagnosis_code}}', ['code', 'description', 'created_at', 'updated_at'], $rows)->execute();
        }

        echo "$insertCount rows inserted.\n";
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%diagnosis_code}}');
    }
}
