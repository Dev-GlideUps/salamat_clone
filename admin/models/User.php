<?php

namespace admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
// use yii\behaviors\BlameableBehavior;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface
{
    /** @var string Plain password. Used for model validation. */
    public $password;

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            // BlameableBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password_hash', 'access_token', 'auth_key', 'registration_ip'], 'required'],
            [['name', 'email', 'phone'], 'trim'],
            ['registration_ip', 'ip'],

            ['name', 'string', 'min' => 3],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => Yii::t('error', 'This email is taken. Try another.')],
            ['password', 'required', 'on' => ['register']],
            ['password', 'string', 'min' => 6, 'max' => 72, 'on' => ['register']],
            ['phone', 'string', 'min' => 8, 'max' => 32],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]+$/', 'message' => Yii::t('error', 'Sorry, only numbers (0-9) are allowed.')],
            ['access_token', 'unique'],
            ['access_token', 'string', 'max' => 64],
            ['auth_key', 'string', 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('general', 'Full name'),
            'email' => Yii::t('general', 'Email address'),
            'password' => Yii::t('user', 'Password'),
            'phone' => Yii::t('general', 'Phone number'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findIdentityByEmail($email)
    {
        return static::findOne(['email' => $email]);
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

    public function getProfilePicPath($absolute = false)
    {
        if ($absolute) {
            return Yii::getAlias('@common/web/img/svg_icons/user.svg');
        }

        return Yii::getAlias('@web/img/svg_icons/user.svg');
    }
}
