<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PatientSearch represents the model behind the search form of `patient\models\Patient`.
 */
class PatientSearch extends Patient
{
    public $profile_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'height', 'weight'], 'integer'],
            [['cpr', 'name', 'name_alt', 'phone', 'profile_id'], 'safe'],
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
        $query = Patient::find()->alias('p')->joinWith(['clinicPatient cp']);

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
            'p.gender' => $this->gender,
            'p.height' => $this->height,
            'p.weight' => $this->weight,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere (['OR',
            ['like', 'p.name', $this->name],
            ['like', 'p.name_alt', $this->name],
        ]);
        $query->andFilterWhere(['like', 'p.cpr', $this->cpr])
            ->andFilterWhere(['like', 'p.phone', $this->phone])
            ->andFilterWhere(['like', 'cp.profile_ref', $this->profile_id]);

        return $dataProvider;
    }
}
