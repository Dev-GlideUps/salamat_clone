<?php

use yii\db\Migration;
use clinic\models\Clinic;
use clinic\models\User;

/**
 * Class m200614_139452_insert_xlsx_users
 */
class m200614_139452_insert_xlsx_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(Yii::getAlias('@console/models/users_0002.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();

        // For Al Senan Medical Center, ID => 7
        $clinic_id = 7;
        $clinic = Clinic::findOne($clinic_id);

        if ($clinic === null) {
            return "No clinic found";
        }

        $authManager = new \clinic\components\rbac\DbManager();

        $rows = 0;
        $inserted = 0;
        foreach ($worksheet->getRowIterator() AS $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = (string) $cell->getValue();
            }
            
            $name = ucwords(trim($cells[1]));
            $role = trim($cells[2]);
            $email = trim($cells[3]);
            $password = trim($cells[4]);
            $phone = trim($cells[5]);

            if ($email != '_email') {
                $model = new User([
                    'clinic_id' => $clinic_id,
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                ]);
                $model->scenario = 'register';

                if ($model->register()) {
                    $model->link('clinics', $clinic, ['created_at' => time()]);
                    $model->updateAttributes(['confirmed_at' => time()]);
                    $authManager->assign($authManager->getRole($role), $model->id, $clinic_id);
                    $inserted++;
                }
                $rows++;
            }
        }

        echo "$inserted users inserted from $rows.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200614_139452_insert_xlsx_users cannot be reverted.\n";
        return false;
    }
}
