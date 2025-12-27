<?php

namespace clinic\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\Diagnosis;

/**
 * DiagnosisSearch represents the model behind the search form of `clinic\models\Diagnosis`.
 */
class DiagnosisSearch extends Diagnosis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'patient_id', 'branch_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code', 'description', 'notes'], 'safe'],
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
        $query = Diagnosis::find()->alias('dg')->joinWith(['patient', 'branch'])->where(['b.clinic_id' => \Yii::$app->user->identity->active_clinic]);

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
            'dg.patient_id' => $this->patient_id,
            'dg.branch_id' => $this->branch_id,
            'dg.created_at' => $this->created_at,
            'dg.updated_at' => $this->updated_at,
            'dg.created_by' => $this->created_by,
            'dg.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'dg.code', $this->code])
            ->andFilterWhere(['like', 'dg.description', $this->description])
            ->andFilterWhere(['like', 'dg.notes', $this->notes]);

        return $dataProvider;
    }
}
