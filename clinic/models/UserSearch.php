<?php

namespace clinic\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\User;

/**
 * UserSearch represents the model behind the search form of `clinic\models\User`.
 */
class UserSearch extends User
{
    public $clinic_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'clinic_id'], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'phone', 'email'], 'safe'],
        ];
    }

    public function attributes()
    {
        return [
            'id',
            'clinic_id',
            'created_at',
            'updated_at',
            'name',
            'phone',
            'email',
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
    public function search($params, $sort = ['defaultOrder' => ['created_at' => SORT_DESC]])
    {
        $this->load($params);
        
        $query = User::find();

        if (!empty($this->clinic_id)) {
            $query = User::find()->joinWith(["clinicLinks x"], true, 'INNER JOIN')->where(['x.clinic_id' => $this->clinic_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
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
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
