<?php

namespace admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Patient;

/**
 * PatientSearch represents the model behind the search form of `patient\models\Patient`.
 */
class PatientSearch extends Patient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'height', 'weight'], 'integer'],
            [['cpr', 'name', 'phone', 'address'], 'safe'],
            [['dob', 'created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d'],
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
        $query = Patient::find();

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
            'gender' => $this->gender,
            'height' => $this->height,
            'weight' => $this->weight,
            'dob' => $this->dob,
        ]);

        $query->andFilterWhere (['OR',
            ['like', 'name', $this->name],
            ['like', 'name_alt', $this->name],
        ]);

        if (!empty($this->created_at)) {
            $time1 = strtotime($this->created_at);
            $time2 = strtotime('+1 day', $time1);
            $query->andFilterWhere(['between', 'created_at', $time1, $time2]);
        }

        if (!empty($this->updated_at)) {
            $time1 = strtotime($this->updated_at);
            $time2 = strtotime('+1 day', $time1);
            $query->andFilterWhere(['between', 'updated_at', $time1, $time2]);
        }

        $query->andFilterWhere(['like', 'cpr', $this->cpr])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
