<?php

namespace patient\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use patient\models\Attachment;

/**
 * AttachmentSearch represents the model behind the search form of `patient\models\Attachment`.
 */
class AttachmentSearch extends Attachment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_id', 'patient_id', 'category_id', 'created_at', 'created_by'], 'integer'],
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
        $query = Attachment::find()->alias('ath')->joinWith(['branch b', 'category cat', 'creator u1']);

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
            'b.clinic_id' => Yii::$app->user->identity->active_clinic,
            'ath.patient_id' => $this->patient_id,
            'ath.category_id' => $this->category_id,
            'ath.created_at' => $this->created_at,
            'ath.created_by' => $this->created_by,
        ]);

        return $dataProvider;
    }
}
