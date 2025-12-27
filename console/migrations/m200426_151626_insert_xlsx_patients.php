<?php

use yii\db\Migration;
use clinic\models\Patient;
use clinic\models\Clinic;
use clinic\models\ClinicPatient;

/**
 * Class m200426_151626_insert_xlsx_patients
 */
class m200426_151626_insert_xlsx_patients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(Yii::getAlias('@console/models/patients_0002.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();

        // For Dr. Mohammed Shaker Eye Clinic, ID => 6
        $clinic_id = 6;
        $clinic = Clinic::findOne($clinic_id);

        if ($clinic === null) {
            return "No clinic found";
        }
        
        $rows = 0;
        $inserted = 0;
        foreach ($worksheet->getRowIterator() AS $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = (string) $cell->getValue();
            }
            
            $profile = trim($cells[0]);
            $cpr = trim($cells[1]);
            $name = ucwords(trim($cells[2]));
            $phone = trim($cells[3]);
            $country = trim($cells[6]);

            if (!empty($cpr) && $cpr != 'CPR') {
                $patient = Patient::findOne(['cpr' => $cpr]);
                if ($patient === null) {
                    $patient = new Patient([
                        'cpr' => $cpr,
                        'nationality' => empty($country) ? 'BH' : $country,
                        'name' => $name,
                        'phone' => $phone,
                    ]);
                }

                if (!$patient->isNewRecord || $patient->save(false)) {
                    $clinicPatient = ClinicPatient::findOne(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                    if ($clinicPatient === null) {
                        $clinicPatient = new ClinicPatient(['clinic_id' => $clinic_id, 'patient_id' => $patient->id]);
                    }
                    $clinicPatient->profile_ref = $profile;
                    $clinicPatient->save(false);

                    $inserted++;
                }
            }
            $rows++;
        }

        echo "$inserted patients inserted from $rows.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200426_151626_insert_xlsx_patients cannot be reverted.\n";
        return false;
    }
}
