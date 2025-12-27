<?php

namespace clinic\models\hr;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\hr\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `clinic\models\hr\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clinic_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'cpr', 'address', 'phone', 'cpr_expiry', 'nationality', 'passport_start', 'passport_expiry', 'visa_expiry', 'residency_start', 'residency_expiry', 'contract_start', 'contract_expiry'], 'safe'],
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
        $query = Employee::find();

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
            'clinic_id' => $this->clinic_id,
            'cpr_expiry' => $this->cpr_expiry,
            'passport_start' => $this->passport_start,
            'passport_expiry' => $this->passport_expiry,
            'visa_expiry' => $this->visa_expiry,
            'residency_start' => $this->residency_start,
            'residency_expiry' => $this->residency_expiry,
            'contract_start' => $this->contract_start,
            'contract_expiry' => $this->contract_expiry,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cpr', $this->cpr])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'nationality', $this->nationality]);

        return $dataProvider;
    }
}
