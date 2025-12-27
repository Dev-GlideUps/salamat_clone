<?php

namespace clinic\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\Doctor;

/**
 * DoctorSearch represents the model behind the search form of `clinic\models\Doctor`.
 */
class DoctorSearch extends Doctor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'speciality', 'experience', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'description', 'mobile', 'language', 'photo'], 'safe'],
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
        $query = Doctor::find()->joinWith(['branches'])->where(['b.clinic_id' => \Yii::$app->user->identity->active_clinic]);

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
            'speciality' => $this->speciality,
            'experience' => $this->experience,
            'user_id' => $this->user_id,
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
