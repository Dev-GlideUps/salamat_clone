<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\SickLeave;

/**
 * SickLeaveSearch represents the model behind the search form of `clinic\models\SickLeave`.
 */
class SickLeaveSearch extends SickLeave
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'patient_id', 'diagnosis_id', 'leave_type', 'advise', 'days', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SickLeave::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'diagnosis_id' => $this->diagnosis_id,
            'leave_type' => $this->leave_type,
            'advise' => $this->advise,
            'days' => $this->days,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
