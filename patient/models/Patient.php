<?php

namespace patient\models;

use Imagine\Image\Box;
use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;
use clinic\models\Clinic;
use clinic\models\Branch;
use clinic\models\Appointment;
use clinic\models\ClinicPatient;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%patient}}".
 *
 * @property int $id
 * @property string $cpr
 * @property string $name
 * @property string|null $name_alt
 * @property string $phone
 * @property int|null $gender
 * @property int|null $height
 * @property int|null $weight
 * @property string|null $photo
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Appointment[] $appointments
 */
class Patient extends \yii\db\ActiveRecord
{
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;

    const STATUS_SINGLE = 0;
    const STATUS_ENGAGED = 1;
    const STATUS_MARRIED = 2;
    const STATUS_WIDOWED = 3;
    const STATUS_DIVORCED = 4;

    const RELATIVE_SPOUSE = 0;
    const RELATIVE_SIBLING = 1;
    const RELATIVE_PARENT = 2;
    const RELATIVE_OFFSPRING = 3;
    const RELATIVE_OTHER = 4;

    public $relative_name;
    public $relative_relation;
    public $relative_phone;

    public $imageFile;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            // BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%patient}}';
    }

    public static function genderList() {
        return [
            self::GENDER_MALE => Yii::t('general', 'Male'),
            self::GENDER_FEMALE => Yii::t('general', 'Female'),
        ];
    }

    public static function statusList() {
        return [
            self::STATUS_SINGLE => Yii::t('general', 'Single'),
            self::STATUS_ENGAGED => Yii::t('general', 'Engaged'),
            self::STATUS_MARRIED => Yii::t('general', 'Married'),
            self::STATUS_WIDOWED => Yii::t('general', 'Widowed'),
            self::STATUS_DIVORCED => Yii::t('general', 'Divorced'),
        ];
    }

    public static function relativeList() {
        return [
            self::RELATIVE_SPOUSE => Yii::t('general', 'Spouse'),
            self::RELATIVE_SIBLING => Yii::t('general', 'Sibling'),
            self::RELATIVE_PARENT => Yii::t('general', 'Parent'),
            self::RELATIVE_OFFSPRING => Yii::t('general', 'Son / Daughter'),
            self::RELATIVE_OTHER => Yii::t('general', 'Other'),
        ];
    }

    public static function bloodTypes() {
        return [
            'O-' => 'O-',
            'O+' => 'O+',
            'A-' => 'A-',
            'A+' => 'A+',
            'B-' => 'B-',
            'B+' => 'B+',
            'AB-' => 'AB-',
            'AB+' => 'AB+',
        ];
    }
    
    public function rules()
    {
        return [
            [['cpr', 'nationality', 'name', 'phone', 'phone_line'], 'required', 'message' => Yii::t('general', '{attribute} is required')],
            [['cpr', 'name', 'phone'], 'trim'],
            [['cpr'], 'unique', 'targetAttribute' => ['nationality', 'cpr'], 'message' => Yii::t('general', '{attribute} is already taken')],
            [['dob'], 'date', 'format' => 'php:Y-m-d'],
            [['gender', 'height', 'weight', 'marital_status', 'relative_relation', 'created_at', 'updated_at'], 'integer'],
            [['cpr', 'emergency_contact'], 'string'],
            [['name', 'name_alt', 'photo', 'address', 'relative_name'], 'string', 'max' => 255],
            [['blood_type'], 'string', 'max' => 6],
            [['phone', 'phone_line', 'relative_phone'], 'string', 'max' => 18],
            [['phone', 'phone_line', 'relative_phone'], 'number'],
            [['imageFile'],'image',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 3072000,
                'tooBig' => Yii::t('general', 'Photo size is too large, it should be less than {formattedLimit}.'),
                'minWidth' => 256,
                'minHeight' => 256,
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'cpr' => Yii::t('general', 'CPR'),
            'nationality' => Yii::t('general', 'Nationality'),
            'name' => Yii::t('general', 'Name (English)'),
            'name_alt' => Yii::t('general', 'Name (Arabic)'),
            'phone' => Yii::t('general', 'Phone number'),
            'phone_line' => Yii::t('general', 'Country'),
            'dob' => Yii::t('general', 'Date of birth'),
            'address' => Yii::t('general', 'Address'),
            'gender' => Yii::t('general', 'Gender'),
            'height' => Yii::t('general', 'Height'),
            'weight' => Yii::t('general', 'Weight'),
            'imageFile' => Yii::t('general', 'Personal photo'),
            'blood_type' => Yii::t('patient', 'Blood type'),
            'marital_status' => Yii::t('patient', 'Marital status'),
            'relative_name' => Yii::t('general', 'Name'),
            'relative_relation' => Yii::t('general', 'Relation'),
            'relative_phone' => Yii::t('general', 'Phone number'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function upload()
    {
        $imageFile = UploadedFile::getInstance($this, 'imageFile');
        $thumbPrefix = 'thumbs/';

        if (empty($imageFile)) {
            return true;
        }

        if ($this->validate('imageFile')) {
            $path = \Yii::getAlias('@patient/documents/patients/photo/');

            if (!empty($this->photo) && file_exists($path . $this->photo)) {
                unlink($path . $this->photo);
            }
            if (!empty($this->photo) && file_exists($path . $thumbPrefix . $this->photo)) {
                unlink($path . $thumbPrefix . $this->photo);
            }

            $file = uniqid('photo_') . '.' . $imageFile->extension;
            while (file_exists($path.$file)) {
                $file = uniqid('photo_') . '.' . $imageFile->extension;
            }

            $imageFile->saveAs($path."base_".$file);
            $this->photo = $file;

            Image::thumbnail($path."base_".$file, 256, 256)->resize(new Box(256,256))->save($path.$file, ['quality' => 80]);
            unlink($path."base_".$file);
            Image::thumbnail($path.$file, 64, 64)->save($path.$thumbPrefix.$file, ['quality' => 80]);

            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $path = \Yii::getAlias('@clinic/documents/patients/photo/');
        if (!empty($this->photo) && file_exists($path . $this->photo)) {
            unlink($path . $this->photo);
        }
        return true;
    }

    public function getAge($fullFormat = false)
    {
        if ($this->dob === null) {
            return null;
        }

        $dob = \DateTime::createFromFormat("Y-m-d", $this->dob);
        $diff = $dob->diff(new \DateTime());

        if ($fullFormat) {
            return Yii::t('general', '{y} years, {m} months, {d} days', [
                'y' => $diff->format("%y"),
                'm' => $diff->format("%m"),
                'd' => $diff->format("%d"),
            ]);
        }

        return Yii::t('general', '{y} years', ['y' => $diff->format("%y")]);
    }

    public function getPhoneNumber()
    {
        if (empty($this->phone_line)) {
            return $this->phone;
        }
        return "+{$this->phone_line} {$this->phone}";
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->emergency_contact !== null) {
            $data = Json::decode($this->emergency_contact);
            $this->relative_name = $data['name'];
            $this->relative_relation = $data['relation'];
            $this->relative_phone = $data['phone'];
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->name = ucwords(strtolower($this->name));
        $data = [
            'name' => ucwords(strtolower($this->relative_name)),
            'relation' => $this->relative_relation,
            'phone' => $this->relative_phone,
        ];
        $this->emergency_contact = Json::encode($data);

        return true;
    }

    public function getPhotoUrl()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/patient/photo/$this->photo"]);
    }

    public function getPhotoThumb()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/patient.svg');
        }
        return \yii\helpers\Url::to(["/patient/thumbnail/$this->photo"]);
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['patient_id' => 'id'])->alias('app');
    }

    /**
     * Gets query for [[ClinicPatient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatientClinics()
    {
        return $this->hasMany(ClinicPatient::className(), ['patient_id' => 'id'])->alias('pcs');
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->via('appointments')->alias('b');
    }
}
