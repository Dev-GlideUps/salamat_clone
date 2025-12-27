<?php

use yii\db\Migration;
use clinic\models\Clinic;
use clinic\models\User;

/**
 * Class m200707_121332_create_clinic_users
 */
class m200707_121332_create_clinic_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // For Al Senan Medical Center, ID => 7
        $clinic_id = 7;
        $clinic = Clinic::findOne($clinic_id);

        if ($clinic === null) {
            return "No clinic found";
        }

        $authManager = new \clinic\components\rbac\DbManager();

        $data = [
            [
                'clinic_id' => $clinic_id,
                'name' => 'Dr. Rehab',
                'email' => strtolower('REHAB_HUJAIRI@HOTMAIL.COM'),
                'password' => '5OJB2ZCM',
                'role' => 'Doctor',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Dr. Tasneem',
                'email' => strtolower('TKAUSAR2@HOTMAIL.COM'),
                'password' => 'W5FQBHCG',
                'role' => 'Doctor',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Dr. Fatima',
                'email' => strtolower('DR.FATIMA@LIVE.COM'),
                'password' => '057UQHVI',
                'role' => 'Doctor',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Pinki',
                'email' => strtolower('PINKYJUSTIN26@GMAIL.COM'),
                'password' => 'L2OAK1I1',
                'role' => 'Medical staff',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Ashly',
                'email' => strtolower('BABU.NATIONALUNIT@GMAIL.COM'),
                'password' => 'S4P8U1GT',
                'role' => 'Medical staff',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Ansa',
                'email' => strtolower('TOMYBIJU756@GMAIL.COM'),
                'password' => '6IUM8L0L',
                'role' => 'Medical staff',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Siji',
                'email' => strtolower('SIJIMOLKS1991@GMAIL.COM'),
                'password' => 'L1DL9HS0',
                'role' => 'Medical staff',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Batool',
                'email' => strtolower('GBEHAJII@GMAIL.COM'),
                'password' => 'XH1ZI87R',
                'role' => 'Reception',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Muna',
                'email' => strtolower('LO8V8E@HOTMAIL.COM'),
                'password' => 'N95X8R1G',
                'role' => 'Reception',
            ],
            [
                'clinic_id' => $clinic_id,
                'name' => 'Mustafa',
                'email' => strtolower('MOSTAFAMOSSA448@GMAIL.COM'),
                'password' => 'KLKSNBPC',
                'role' => 'Accountant',
            ],
        ];

        $inserted = 0;
        foreach ($data as $userData) {
            $role = $authManager->getRole($userData['role']);
            unset($userData['role']);
            $model = new User($userData);
            $model->scenario = 'register';

            if ($model->register()) {
                $model->link('clinics', $clinic, ['created_at' => time()]);
                $model->updateAttributes(['confirmed_at' => time()]);
                $authManager->assign($role, $model->id, $clinic->id);

                echo "$model->email\n";
                echo "$model->password\n\n";

                $inserted++;
            }
        }

        echo "$inserted users inserted from ".count($data).".\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200707_121332_create_clinic_users cannot be reverted.\n";
        return false;
    }
}
