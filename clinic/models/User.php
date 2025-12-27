<?php

namespace clinic\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface
{
    /** @var string Plain password. Used for model validation. */
    public $password;
    public $clinic_id;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            // BlameableBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return '{{%clinic_user}}';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password_hash', 'access_token', 'auth_key', 'registration_ip'], 'required'],
            [['password', 'clinic_id'], 'required', 'on' => ['register']],
            [['name', 'email', 'phone'], 'trim'],
            ['registration_ip', 'ip'],
            ['name', 'string', 'min' => 3],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => Yii::t('error', 'This email is taken. Try another.')],
            ['password', 'string', 'min' => 6, 'max' => 72, 'on' => ['register']],
            ['phone', 'string', 'min' => 8, 'max' => 32],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]+$/', 'message' => Yii::t('error', 'Sorry, only numbers (0-9) are allowed.')],
            ['dark_theme', 'boolean'],
            ['dark_theme', 'default', 'value' => 0],
            ['access_token', 'unique'],
            ['access_token', 'string', 'max' => 64],
            ['auth_key', 'string', 'max' => 32],
            ['clinic_id', 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            ['active_clinic', 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['active_clinic' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'name' => Yii::t('general', 'Full name'),
            'email' => Yii::t('general', 'Email address'),
            'password' => Yii::t('user', 'Password'),
            'phone' => Yii::t('general', 'Phone number'),
            'dark_theme' => Yii::t('general', 'Dark theme'),
            'active_clinic' => Yii::t('clinic', 'Active clinic'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
            'password_updated_at' => Yii::t('user', 'Last password update'),
            'last_login_at' => Yii::t('user', 'Last sign-in'),
            'confirmed_at' => Yii::t('user', 'Account confirmation'),
            'created_at' => Yii::t('general', 'Created'),
            'updated_at' => Yii::t('general', 'Updated'),
        ];
    }

    public static function findIdentity($id)
    {
        return static::find()->joinWith(['activeClinicLink'])->where(['id' => $id])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->joinWith(['activeClinicLink'])->where(['access_token' => $token])->one();
    }

    public static function findIdentityByEmail($email)
    {
        return static::find()->joinWith(['activeClinicLink'])->where(['email' => $email])->one();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getIsConfirmed()
    {
        if (empty($this->confirmed_at)) {
            return false;
        }

        return true;
    }

    public function getIsBlocked()
    {
        if ($this->activeClinicLink === null) {
            return false;
        }
        
        return $this->activeClinicLink->getIsBlocked();
    }

    public function register()
    {
        if (!$this->isNewRecord) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if (!$this->validate('password')) {
            return false;
        }

        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        
        while (!$this->validate('access_token')) {
            $this->access_token = Yii::$app->security->generateRandomString(64);
        }

        $this->auth_key = Yii::$app->security->generateRandomString();

        try {
            $this->registration_ip = Yii::$app->request->userIP;
        } catch (\Exception $e) {
            $this->registration_ip = "::1";
        }

        return $this->save();
    }

    // public function block()
    // {
    //     return $this->activeClinicLink->block();
    // }

    // public function unblock()
    // {
    //     return $this->activeClinicLink->unblock();
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinics() {
        return $this->hasMany(Clinic::className(), ['id' => 'clinic_id'])->via('clinicLinks')->alias('c');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClinicLinks() {
        return $this->hasMany(ClinicLink::className(), ['user_id' => 'id'])->alias('cl');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveClinicLink() {
        return $this->hasOne(ClinicLink::className(), ['user_id' => 'id', 'clinic_id' => 'active_clinic'])->alias('acl');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveClinic() {
        return $this->hasOne(Clinic::className(), ['id' => 'active_clinic'])->alias('ac');
    }
    
    public function getDoctor() {
        return $this->hasOne(Doctor::className(), ['user_id' => 'id'])->alias('d');
    }
    
    public function getIsDoctor() {
        return $this->doctor !== null;
    }

    public function getProfilePicPath($absolute = false)
    {
        if ($absolute) {
            return Yii::getAlias('@common/web/img/svg_icons/user.svg');
        }

        return Yii::getAlias('@web/img/svg_icons/user.svg');
    }
    
    public function getPasswordChangeDate($longFormat = false)
    {
        if ($longFormat) {
            return Yii::$app->formatter->asDateTime($this->password_updated_at);
        }

        return Yii::$app->formatter->asDate($this->password_updated_at);
    }
}
