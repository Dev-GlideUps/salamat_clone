<?php

namespace patient\models;

use Yii;
use yii\web\UploadedFile;
use clinic\models\Branch;
use clinic\models\User;

/**
 * This is the model class for table "{{%patient_attachment}}".
 *
 * @property int $id
 * @property int $branch_id
 * @property int $patient_id
 * @property int $category_id
 * @property int $path
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property AttachmentCategory $category
 * @property Clinic $clinic
 * @property Patient $patient
 */
class Attachment extends \yii\db\ActiveRecord
{
    public $attachmentFile;

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
    public static function tableName()
    {
        return '{{%patient_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'patient_id', 'category_id', 'path'], 'required'],
            [['branch_id', 'patient_id', 'category_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['path'], 'string'],
            [['category_id'], 'exist', 'targetClass' => AttachmentCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['branch_id'], 'exist', 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['patient_id'], 'exist', 'targetClass' => Patient::className(), 'targetAttribute' => ['patient_id' => 'id']],

            [
                'attachmentFile',
                'file',
                'skipOnEmpty' => true,
                'extensions' => ['pdf', 'jpg', 'png'],
                'maxSize' => 1024 * 1024 * 5, // 5Mb
                'tooBig' => Yii::t('general', 'Attchment file size is too large, it should be less than {formattedLimit}.'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'branch_id' => Yii::t('clinic', 'Branch'),
            'patient_id' => Yii::t('patient', 'Patient'),
            'category_id' => Yii::t('general', 'Category'),
            'path' => Yii::t('general', 'File path'),
            'created_at' => Yii::t('general', 'Created At'),
            'updated_at' => Yii::t('general', 'Updated At'),
            'created_by' => Yii::t('general', 'Created By'),
            'updated_by' => Yii::t('general', 'Updated By'),
        ];
    }

    public function getFilePath()
    {
        if (!empty($this->path) && file_exists(\Yii::getAlias("@clinic/documents/attachments/{$this->path}"))) {
            return \Yii::getAlias("@clinic/documents/attachments/{$this->path}");
        }
        return '';
    }

    public function upload()
    {
        $attachmentFile = UploadedFile::getInstance($this, 'attachmentFile');

        if (empty($attachmentFile)) {
            return false;
        }

        // if (!empty($this->path) && file_exists(\Yii::getAlias("@clinic/documents/attachments/{$this->path}"))) {
        //     unlink(\Yii::getAlias("@clinic/documents/attachments/{$this->path}"));
        // }

        if ($this->validate('attachmentFile')) {
            $path = \Yii::getAlias("@clinic/documents/attachments/{$this->branch->clinic_id}/patients");
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file = uniqid("p{$this->patient_id}_") . '.' . $attachmentFile->extension;
            while (file_exists("{$path}/{$file}")) {
                $file = uniqid("p{$this->patient_id}_") . '.' . $attachmentFile->extension;
            }

            $attachmentFile->saveAs("$path/$file");
            $this->path = "{$this->branch->clinic_id}/patients/{$file}";

            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AttachmentCategory::className(), ['id' => 'category_id'])->alias('cat');
    }

    /**
     * Gets query for [[Clinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id'])->via('branch')->alias('c');
    }
    
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
    
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->alias('u1');
    }
}
