<?php

use console\models\Migration;

/**
 * Handles the creation of table `{{%speciality}}`.
 */
class m190916_210515_create_speciality_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%speciality}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'title_ar' => $this->string()->notNull(),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        // add foreign key for table `{{%doctor}}`
        $this->addForeignKey(
            '{{%fk-doctor-speciality-speciality}}',
            '{{%doctor}}',
            'speciality',
            '{{%speciality}}',
            'id',
            'SET NULL'
        );

        $rows = [
            ['Cardiology (Heart)', 'القلب', '1581349781', '1581349781'],
            ['Gastroenterology', 'الجهاز الهضمي', '1581349781', '1581349781'],
            ['Hepatology', 'الكبد', '1581349781', '1581349781'],
            ['Gynecology', 'طب النساء', '1581349781', '1581349781'],
            ['Plastic surgery', 'الجراحة تجميلية', '1581349781', '1581349781'],
            ['Internal Medicine', 'الطب الباطني', '1581349781', '1581349781'],
            ['Endocrinology', 'علم الغدد', '1581349781', '1581349781'],
            ['Dental (Teeth)', 'الأسنان', '1581349781', '1581349781'],
            ['Urology', 'المسالك البولية', '1581349781', '1581349781'],
            ['General Medicine (Primary Care)', 'الطب العام', '1581349781', '1581349781'],
            ['Dermatology (Skin)', 'الجلدية', '1581349781', '1581349781'],
            ['ENT (Otolaryngology)', 'الأنف والأذن والحنجرة', '1581349781', '1581349781'],
            ['Physiotherapy', 'العلاج الطبيعي', '1581349781', '1581349781'],
            ['Family Medicine', 'طب الأسرة', '1581349781', '1581349781'],
            ['Rheumatology', 'الروماتيزم', '1581349781', '1581349781'],
            ['Interventional Pain Management', 'إدارة الألم التداخلي', '1581349781', '1581349781'],
            ['Podiatric (Knee & Ankle)', 'علاج الأرجل (الركبة والكاحل)', '1581349781', '1581349781'],
            ['Speech - Language Therapy', 'علاج التحدث', '1581349781', '1581349781'],
            ['Nutrition', 'التغذية', '1581349781', '1581349781'],
            ['Sleep Medicine', 'طب النوم', '1581349781', '1581349781'],
            ['Dietitian', 'أخصائي تغذية', '1581349781', '1581349781'],
            ['Radiology', 'طب إشعاعي', '1581349781', '1581349781'],
            ['Metabolic Surgery', '', '1581349781', '1581349781'],
            ['Aromatherapy', 'العلاج العطري', '1581349781', '1581349781'],
            ['Hijama (Cupping)', 'الحجامة', '1581349781', '1581349781'],
            ['Hypnotherapy', 'العلاج بالتنويم المغناطيسي', '1581349781', '1581349781'],
            ['Acupuncture', 'العلاج بالإبر', '1581349781', '1581349781'],
            ['Emergency Medicine', 'طب الطوارئ', '1581349781', '1581349781'],
            ['Laboratory', 'مختبر', '1581349781', '1581349781'],
            ['Vascular medicine', 'طب الأوعية الدموية', '1581349781', '1581349781'],
            ['Home Care Service', 'خدمة الرعاية المنزلية', '1581349781', '1581349781'],
            ['Laser', 'الليزر', '1581349781', '1581349781'],
            ['Psychology', 'علم النفس', '1581349781', '1581349781'],
            ['pulmonology', 'أمراض الرئة', '1581349781', '1581349781'],
            ['Aviation Medicine', 'طب الطيران', '1581349781', '1581349781'],
            ['Nephrology', 'طب الكلى', '1581349781', '1581349781'],
            ['Neurosurgery', 'جراحة الأعصاب', '1581349781', '1581349781'],
            ['Hematology (Blood)', 'أمراض الدم', '1581349781', '1581349781'],
            ['Oncology (Cancer)', 'الأورام (السرطان)', '1581349781', '1581349781'],
            ['Homeopathy', 'علاج بالمواد الطبيعية', '1581349781', '1581349781'],
            ['Obstetrics & Gynecology', 'أمراض النساء والولادة', '1581349781', '1581349781'],
            ['Anesthesiology', 'التخدير', '1581349781', '1581349781'],
            ['General Surgeon', 'الجراحة العامة', '1581349781', '1581349781'],
            ['Beauty Therapy', 'الجمال', '1581349781', '1581349781'],
            ['Foot Care', 'العناية بالقدم', '1581349781', '1581349781'],
            ['Alternative Medicine', 'الطب البديل', '1581349781', '1581349781'],
            ['Hydrotherapy', 'العلاج المائي', '1581349781', '1581349781'],
        ];

        Yii::$app->db->createCommand()->batchInsert('{{%speciality}}', ['title', 'title_ar', 'created_at', 'updated_at'], $rows)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `{{%doctor}}`
        $this->dropForeignKey(
            '{{%fk-doctor-speciality-speciality}}',
            '{{%doctor}}'
        );

        $this->dropTable('{{%speciality}}');
    }
}
