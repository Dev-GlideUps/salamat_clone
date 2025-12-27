<?php

namespace clinic\models\clinic;

use Yii;
use clinic\models\Clinic;
use clinic\models\Appointment;

/**
 * This is the model class for table "{{%clinic_appointment_sms}}".
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $mobile
 * @property string|null $message
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Clinic $clinic
 */
class AppointmentSms extends \yii\db\ActiveRecord
{
    const SMS_API = "https://www.amwajsms.com/api/";

    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_FAILED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_COMPLETED = 4;

    /**
     * {@inheritdoc}
     */
    public $count;
    public static function tableName()
    {
        return '{{%clinic_appointment_sms}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    public static function statusList() {
        return [
            self::STATUS_PENDING => Yii::t('general', 'Pending'),
            self::STATUS_SENT => Yii::t('general', 'Sent'),
            self::STATUS_FAILED => Yii::t('general', 'Failed'),
            self::STATUS_CANCELED => Yii::t('general', 'Canceled'),
            self::STATUS_COMPLETED => Yii::t('general', 'Completed'),
        ];
    }

    public function getStatusLabel()
    {
        $list = self::statusList();
        
        if (isset($list[$this->status])) {
            return $list[$this->status];
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'mobile'], 'required'],
            [['clinic_id', 'status', ], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d'],

            [['message', 'response'], 'string'],
            [['mobile'], 'string', 'max' => 255],
            [['clinic_id'], 'exist', 'targetClass' => Clinic::className(), 'targetAttribute' => ['clinic_id' => 'id']],
            [['appointment_id'], 'exist', 'targetClass' => Appointment::className(), 'targetAttribute' => ['appointment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('general', 'ID'),
            'clinic_id' => Yii::t('clinic', 'Clinic'),
            'appointment_id' => Yii::t('clinic', 'Appointment'),
            'mobile' => Yii::t('general', 'Mobile'),
            'message' => Yii::t('general', 'Message'),
            'status' => Yii::t('general', 'Status'),
            'response' => Yii::t('general', 'API response'),
            'created_at' => Yii::t('general', 'Created at'),
            'updated_at' => Yii::t('general', 'Updated at'),
        ];
    }

    /**
     * Gets query for [[Clinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id' => 'clinic_id']);
    }

    public function getAppointment()
    {
        return $this->hasOne(Appointment::className(), ['id' => 'appointment_id']);
    }

    public function sendSMS()
    {
        $curl = new \Curl\Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);

        $url = self::SMS_API . "SendSMS";
        $data = array_merge(Yii::$app->params['amwajSMS'], [
            'sender' => 'Salamat',
            'recipients' => $this->mobile,
            'message' => $this->message,
        ]);

        $curl->post($url, $data);
        $response = $curl->response;

        $status = self::STATUS_SENT;

        if ($response === false) {
            $response = [
                'error' => $curl->errorMessage,
            ];
            $status = self::STATUS_FAILED;
        }

        $this->updateAttributes(['response' => \yii\helpers\Json::encode($response), 'status' => $status]);
        return $response;
    }
}
