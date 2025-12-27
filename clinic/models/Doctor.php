<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "{{%doctor}}".
 *
 * @property int $id
 * @property string $name
 * @property int $speciality
 * @property string $description
 * @property int $experience
 * @property string $mobile
 * @property string $language
 * @property string $photo
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class Doctor extends \yii\db\ActiveRecord
{
    public $languageArray;
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
        return '{{%doctor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_alt', 'languageArray', 'speciality'], 'required'],
            [['speciality', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'language'], 'string'],
            [['name', 'name_alt', 'mobile', 'photo'], 'string', 'max' => 255],
            [['languageArray'], 'each', 'rule' => ['string']],
            [['experience'], 'date', 'format' => 'php:Y-m-d'],
            [['imageFile'], 'image',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 3072000,
                'tooBig' => Yii::t('general', 'Photo size is too large, it should be less than {formattedLimit}.'),
                'minWidth' => 256,
                'minHeight' => 256,
                // 'maxWidth' => 512,
                // 'maxHeight' => 512,
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
            'name' => Yii::t('general', 'Name (English)'),
            'name_alt' => Yii::t('general', 'Name (Arabic)'),
            'speciality' => Yii::t('clinic', 'Speciality'),
            'description' => Yii::t('general', 'Description'),
            'experience' => Yii::t('clinic', 'In practice since'),
            'mobile' => Yii::t('general', 'Mobile'),
            'language' => Yii::t('general', 'Languages'),
            'languageArray' => Yii::t('general', 'Languages'),
            'imageFile' => Yii::t('general', 'Personal photo'),
            'user_id' => Yii::t('general', 'User ID'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function getLanguages()
    {
        if (empty($this->language)) {
            return [];
        }
        
        return Json::decode($this->language);
    }

    public function setLanguages($array)
    {
        if (empty($array)) {
            $array = [];
        }
        
        $this->language = Json::encode($array);
    }

    public function getLanguagesText()
    {
        $list = \common\models\Languages::list();
        $result = [];
        foreach ($this->languages as $lang) {
            $result[] = $list[$lang];
        }

        return implode(", ", $result);
    }

    public function upload()
    {
        $imageFile = UploadedFile::getInstance($this, 'imageFile');
        $thumbPrefix = 'thumbs/';

        if (empty($imageFile)) {
            return true;
        }

        if ($this->validate('imageFile')) {
            $path = \Yii::getAlias('@clinic/documents/doctors/photo/');

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

    public function getPhotoUrl()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/doctor.svg');
        }
        return \yii\helpers\Url::to(["/clinic/doctors/photo/$this->photo"]);
    }

    public function getPhotoThumb()
    {
        if (empty($this->photo)) {
            return Yii::getAlias('@web/img/doctor.svg');
        }
        return \yii\helpers\Url::to(["/clinic/doctors/thumbnail/$this->photo"]);
    }

    public function getSpecialization()
    {
        return $this->hasOne(Speciality::className(), ['id' => 'speciality'])->alias('s');
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $path = \Yii::getAlias('@clinic/documents/doctors/photo/');
        if (!empty($this->photo) && file_exists($path . $this->photo)) {
            unlink($path . $this->photo);
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->name = ucwords(strtolower($this->name));
        return true;
    }

    public function getDoctorSchedule()
    {
        return $this->hasMany(DoctorClinicBranch::className(), ['doctor_id' => 'id'])->alias('ds');
    }

    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->via('doctorSchedule')->alias('b');
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('u');
    }
}
