<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PatientConsent;

/**
 * PatientConsentSearch represents the model behind the search form of `app\models\PatientConsent`.
 */
class PatientConsentSearch extends PatientConsent
{
    /**
     * {@inheritdoc}
     */
    public $clinic_id;
    public function rules()
    {
        return [
            [['id', 'patient_id', 'consent_id', 'created_at', 'updated_at','clinic_id'], 'integer'],
            [['consent_date', 'signature'], 'safe'],
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
        $query = PatientConsent::find()
        ->innerJoin('consent_form', 'patient_consent.consent_id = consent_form.id');

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
            'consent_id' => $this->consent_id,
            'consent_date' => $this->consent_date,
            'clinic_id' => $this->clinic_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'signature', $this->signature]);

        return $dataProvider;
    }
}
