<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\Appointment;

/**
 * AppointmentSearch represents the model behind the search form of `clinic\models\Appointment`.
 */
class AppointmentSearch extends Appointment
{
    public $cpr;
    public $name;
    public $phone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_id', 'branch_id', 'status'], 'integer'],
            [['cpr', 'name'], 'safe'],
            [['phone'], 'number'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            // [['date'], 'default', 'value' => date('Y-m-d')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return array_merge([
            'cpr' => Yii::t('patient', 'CPR'),
            'name' => Yii::t('general', 'Name'),
            'phone' => Yii::t('general', 'Phone'),
        ], parent::attributeLabels());
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Appointment::find()->joinWith(['branch b', 'patient p']);

        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date' => SORT_DESC, 'time' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            'doctor_id' => $this->doctor_id,
            'branch_id' => $this->branch_id,
            'status' => $this->status,
            'date' => $this->date,
        ]);
        $query->andFilterWhere (['OR',
            ['like', 'p.name', $this->name],
            ['like', 'p.name_alt', $this->name],
        ]);
        $query->andFilterWhere(['like', 'p.cpr', $this->cpr])
            ->andFilterWhere(['like', 'p.phone', $this->phone]);

        return $dataProvider;
    }
}
