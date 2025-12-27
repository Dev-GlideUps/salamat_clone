<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use admin\models\User as Admin;
// use yii\helpers\Json;

// return ExitCode::UNSPECIFIED_ERROR;
// return ExitCode::OK;

class SeedController extends Controller
{
    public $faker;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->faker = \Faker\Factory::create();
    }

    public function actionAdminUser($name, $phone, $email, $password)
    {
        $admin = new Admin([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
        ]);
        $admin->scenario = 'register';

        if ($admin->register()) {
            echo "Admin user created\n";
            return ExitCode::OK;
        } else {
            echo "Error:\n";
            var_dump($admin->getErrorSummary(true));
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    public function actionInsert()
    {
        $rowsNum = 0;
        // $rows = [];
        // for ($i = 0; $i < 2154; $i++) {
        //     $date = $this->faker->dateTimeBetween('-9 months');
        //     $rows[] = [2, 1, 2, ($i % 4), $date->format('Y-m-d'), $date->format('H:i:00'), 15, date('H:i:00', ($date->getTimestamp() + 900)), 15, '{"title":"Service 1","title_alt":"خدمة 1"}', $date->getTimestamp(), $date->getTimestamp(), 1, 1];
        // }

        // $rowsNum = Yii::$app->db->createCommand()->batchInsert('{{%appointment}}', [
        //     'patient_id',
        //     'doctor_id',
        //     'branch_id',
        //     'status',
        //     'date',
        //     'time',
        //     'duration',
        //     'end_time',
        //     'price',
        //     'service',
        //     'created_at',
        //     'updated_at',
        //     'created_by',
        //     'updated_by',
        // ], $rows)->execute();

        echo "$rowsNum records inserted\n";
        if ($rowsNum > 0) {
            return ExitCode::OK;
        } else {
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
