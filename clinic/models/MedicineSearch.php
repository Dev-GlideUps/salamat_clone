<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\Medicine;

/**
 * MedicineSearch represents the model behind the search form of `clinic\models\Medicine`.
 */
class MedicineSearch extends Medicine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clinic_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'forms'], 'safe'],
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
        $query = Medicine::find();

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
            'clinic_id' => Yii::$app->user->identity->active_clinic,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'forms', $this->forms]);

        return $dataProvider;
    }
}
