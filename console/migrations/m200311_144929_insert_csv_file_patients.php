<?php

use yii\db\Migration;
use clinic\models\Patient;
use clinic\models\Clinic;
use clinic\models\ClinicPatient;

/**
 * Class m200311_144929_insert_csv_file_patients
 */
class m200311_144929_insert_csv_file_patients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // For Dr. Ahmed Alsalem Clinic, ID => 5
        $clinic_id = 5;
        $clinic = Clinic::findOne($clinic_id);

        if ($clinic === null) {
            return "No clinic found";
        }

        if (($handle = fopen(Yii::getAlias('@console/models/patients_0001.csv'), "r")) !== false) {
            $row = 0;
            $inserted = 0;
            $header;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($row == 0) {
                    $header = $data;
                    $row++;
                    continue;
                }
                $attributes = [];
                foreach ($header as $i => $item) {
                    $attributes[$item] = trim($data[$i]);
                }
                
                $cpr = str_pad($attributes['SSN'], 9, "0", STR_PAD_LEFT);
                if (strlen($cpr) <= 9 && !empty($attributes['SSN'])) {
                    $patient = Patient::findOne(['cpr' => $cpr]);
                    if ($patient === null) {
                        $patient = new Patient(['cpr' => $cpr]);
                    }

                    $patient->name = $attributes['Display Name'];
                    $patient->nationality = 'BH';
                    $patient->phone = substr($attributes['Mobile Phone'], 0, 8);

                    if (!empty($attributes['Gender'])) $patient->gender = ($attributes['Gender'] == 'M' ? Patient::GENDER_MALE : Patient::GENDER_FEMALE);
                    if (!empty($attributes['Home Street'])) $patient->address = $attributes['Home Street'];
                    
                    if ($patient->save(false)) {
                        $clinicPatient = ClinicPatient::findOne(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                        if ($clinicPatient === null) {
                            $clinicPatient = new ClinicPatient(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                        }
                        $clinicPatient->profile_ref = $attributes['PatientNo'];
                        $clinicPatient->save(false);

                        $inserted++;
                    }
                }
                $row++;
            }
            fclose($handle);

            echo "$inserted patients inserted from $row.\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200311_144929_insert_csv_file_patients cannot be reverted.\n";
        return false;
    }
}
