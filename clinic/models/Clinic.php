<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

// use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%clinic}}".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $terms_conditions
 * @property int $created_at
 * @property int $updated_at
 * @property int $tax_account

 *
 * @property ClinicUser[] $clinicUsers
 * @property ClinicUserRelation[] $clinicUserRelations
 * @property ClinicUser[] $users
 */
class Clinic extends \yii\db\ActiveRecord
{
    public $packageArray;
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
        return '{{%clinic}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'name_alt', 'phone', 'vat_account','tax_account', 'logo'], 'string', 'max' => 255],
            [['package'], 'string'],
            [['appointment_sms'], 'boolean'],
            [['appointment_sms'], 'default', 'value' => 0],
            [['packageArray'], 'each', 'rule' => ['string']],
            [['invoice_terms'], 'string', 'max' => 500],
            [['imageFile'], 'image',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg'],
                'maxSize' => 3072000,
                'tooBig' => Yii::t('general', 'Photo size is too large, it should be less than {formattedLimit}.'),
                'minWidth' => 512,
                'minHeight' => 512,
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
            'name' => Yii::t('clinic', 'Clinic Name (English)'),
            'name_alt' => Yii::t('clinic', 'Clinic Name (Arabic)'),
            'phone' => Yii::t('general', 'Phone'),
            'packageArray' => Yii::t('clinic', 'Sub-systems'),
            'vat_account' => Yii::t('finance', 'VAT account number'),
            'tax_account' => Yii::t('finance', 'Tax account number'),

            'invoice_terms' => Yii::t('clinic', 'Invoice terms'),
            'appointment_sms' => Yii::t('clinic', 'Appointments SMS notification'),
            'logo' => Yii::t('clinic', 'Clinic logo'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public function upload()
    {
        $imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (empty($imageFile)) {
            return true;
        }

        if ($this->validate('imageFile')) {
            $path = \Yii::getAlias('@clinic/documents/clinics/logo/');

            if (!empty($this->logo) && file_exists($path . $this->logo)) {
                unlink($path . $this->logo);
            }

            $file = uniqid('logo_') . '.' . $imageFile->extension;
            while (file_exists($path.$file)) {
                $file = uniqid('logo_') . '.' . $imageFile->extension;
            }

            $imageFile->saveAs($path."base_".$file);
            $this->logo = $file;

            Image::resize($path."base_".$file, 1200, 600)->save($path.$file, ['quality' => 90]);
            unlink($path."base_".$file);

            return true;
        } else {
            return false;
        }
    }

    public static function packages()
    {
        return [
            'dental' => Yii::t('clinic', 'Dental system'),
        ];
    }

    public function getPackages()
    {
        $packages = \yii\helpers\Json::decode($this->package);
        if (empty($packages)) {
            return [];
        }
        return $packages;
    }

    public function setPackages($packages)
    {
        if (empty($packages)) {
            $packages = [];
        }
        $this->package = \yii\helpers\Json::encode($packages);
    }

    public function has($system)
    {
        return in_array($system, $this->packages);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('clinicLinks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinicLinks()
    {
        return $this->hasMany(ClinicLink::className(), ['clinic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['clinic_id' => 'id']);
    }
}
