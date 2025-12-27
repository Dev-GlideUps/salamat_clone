<?php

use yii\db\Migration;
use clinic\models\Diagnosis;

/**
 * Class m200318_142635_add_appointment_id_to_diagnosis_table
 */
class m200318_142635_add_appointment_id_to_diagnosis_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%patient_diagnosis}}', 'appointment_id', $this->integer());

        // add foreign key for table `{{%appointment}}`
        $this->addForeignKey(
            '{{%fk-patient_diagnosis-appointment_id}}',
            '{{%patient_diagnosis}}',
            'appointment_id',
            '{{%appointment}}',
            'id',
            'CASCADE'
        );

        foreach (Diagnosis::find()->joinWith('patient')->all() as $item) {
            $c = count($item->patient->appointments);
            if ($c > 0) {
                foreach ($item->patient->appointments as $app) {
                    if (date('Y-m-d', $app->created_at) == date('Y-m-d', $item->created_at)) {
                        $item->appointment_id = $app->id;
                        break;
                    }
                }
                if ($item->appointment_id === null) {
                    $item->appointment_id = $item->patient->appointments[($c -1)]->id;
                }
                $item->updateAttributes(['appointment_id']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200318_142635_add_appointment_id_to_diagnosis_table cannot be reverted.\n";
        return false;
    }
}
