<?php

namespace clinic\models\dental;

use Yii;
use clinic\models\Clinic;
use clinic\models\Branch;
use clinic\models\Patient;
use clinic\models\Doctor;

/**
 * This is the model class for table "{{%dental_record}}".
 *
 * @property int $id
 * @property int $procedure_id
 * @property int $patient_id
 * @property int $branch_id
 * @property string $teeth
 * @property string|null $notes
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Branch $branch
 * @property Patient $patient
 * @property Procedure $procedure
 */
class Record extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dental_record}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['procedure_id'], 'required', 'message' => Yii::t('patient', 'Please select a {attribute}')],
            [['patient_id', 'branch_id', 'teeth', 'procedure_date'], 'required'],
            [['procedure_id', 'patient_id', 'branch_id', 'teeth', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['notes'], 'string'],
            [['procedure_date'], 'date', 'format' => 'php:Y-m-d'],
            [['branch_id'], 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['procedure_id'], 'exist', 'targetClass' => Procedure::className(), 'targetAttribute' => ['procedure_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'procedure_id' => Yii::t('clinic', 'Procedure'),
            'patient_id' => Yii::t('patient', 'Patient'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'teeth' => Yii::t('patient', 'Teeth'),
            'notes' => Yii::t('general', 'Notes'),
            'procedure_date' => Yii::t('patient', 'Procedure date'),
            'created_at' => Yii::t('general', 'Created At'),
            'updated_at' => Yii::t('general', 'Updated At'),
            'created_by' => Yii::t('general', 'Created By'),
            'updated_by' => Yii::t('general', 'Updated By'),
        ];
    }

    public function getCssClass()
    {
        if (empty($this->procedure->chart_class)) {
            return $this->category->chart_class;
        }
        return $this->procedure->chart_class;
    }

    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->alias('c')->via('branch');
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id'])->alias('b');
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id'])->alias('p');
    }

    /**
     * Gets query for [[Procedure]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcedure()
    {
        return $this->hasOne(Procedure::className(), ['id' => 'procedure_id'])->alias('proc');
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->via('procedure')->alias('cat');
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['user_id' => 'created_by'])->alias('doc');
    }
}
