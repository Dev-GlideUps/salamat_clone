<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\SickLeave;

/**
 * SickLeaveSearch represents the model behind the search form of `clinic\models\SickLeave`.
 */
class SickLeaveSearch extends SickLeave
{
    public $cpr;
    public $name;
    public $phone;
    public $doctor_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'number'],
            [['cpr', 'name'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['created_by', 'doctor_id'], 'integer'],
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
            'cpr' => Yii::t('patient', 'Patient CPR'),
            'name' => Yii::t('patient', 'Patient name'),
            'phone' => Yii::t('patient', 'Patient phone'),
            'doctor_id' => Yii::t('clinic', 'Doctor'),
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
        $query = SickLeave::find()->alias('sl')->joinWith(['branch b', 'patient p', 'doctor d'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'd.id' => $this->doctor_id,
        ]);

        if (!empty($this->created_at)) {
            $start = strtotime($this->created_at);
            $end = strtotime("+1 day", $start);

            $query->andFilterWhere (['AND',
                ['>=', 'sl.created_at', $start],
                ['<', 'sl.created_at', $end],
            ]);
        }

        $query->andFilterWhere (['OR',
            ['like', 'p.name', $this->name],
            ['like', 'p.name_alt', $this->name],
        ]);
        $query->andFilterWhere(['like', 'p.cpr', $this->cpr])
            ->andFilterWhere(['like', 'p.phone', $this->phone]);

        return $dataProvider;
    }
}
