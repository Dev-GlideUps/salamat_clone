<?php

use yii\db\Migration;
use clinic\models\Patient;
use clinic\models\Clinic;
use clinic\models\ClinicPatient;

/**
 * Class m201021_200555_import_xls_patients
 */
class m201021_200555_import_xls_patients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // For Smile Clinic, ID => 13
        $clinic_id = 13;
        $clinic = Clinic::findOne($clinic_id);

        if ($clinic === null) {
            return "No clinic found";
        }
        echo "Inserting patients for {$clinic->name}:\n";

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(Yii::getAlias('@console/models/patients_0003.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();
        
        $rows = 0; //2100;
        $inserted = 0; //1474;
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            
            foreach ($cellIterator as $cell) {
                $cells[] = (string) $cell->getValue();
            }

            unset($row, $cellIterator);
            
            $profile = trim($cells[0]);

            $patientData = [
                'cpr' => substr(trim($cells[22]), 0, 9),
                'nationality' => 'BH',
                'name' => ucwords(trim(trim(trim($cells[2])." ".trim($cells[3]))." ".trim($cells[1]))),
                'phone' => substr(trim($cells[12]), 0, 8),
                'dob' => strtotime(trim($cells[14])) == 978307200 ? NULL : date('Y-m-d', strtotime(trim($cells[14]))),
                'address' => trim($cells[6]),
                'gender' => trim($cells[17]),
            ];

            unset($cells);

            if (!empty($patientData['cpr']) && $patientData['cpr'] != 'ChartNumber') {
                $patient = Patient::findOne(['cpr' => $patientData['cpr'], 'nationality' => $patientData['nationality']]);
                if ($patient === null) {
                    $patient = new Patient($patientData);
                }

                if (!$patient->isNewRecord || $patient->save()) {
                    $clinicPatient = ClinicPatient::findOne(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                    if ($clinicPatient === null) {
                        $clinicPatient = new ClinicPatient(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                    }
                    $clinicPatient->profile_ref = $profile;
                    $clinicPatient->save();

                    $inserted++;
                }
            }

            unset($patientData, $patient, $clinicPatient);

            $rows++;

            if ($rows % 300 == 0) {
                echo "$inserted patients inserted from $rows.\n";
            }
        }

        echo "$inserted patients inserted from $rows.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201021_200555_import_xls_patients cannot be reverted.\n";
        return false;
    }
}
