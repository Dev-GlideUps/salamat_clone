<?php

namespace clinic\models\rbac;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\ClinicLink;

/**
 * AssignmentSearch represents the model behind the search form about Assignment.
 */
class AssignmentSearch extends ClinicLink
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clinic_id'], 'integer'],
            [['email'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['email'] = Yii::t('general', 'Email address');
        return $labels;
    }

    public function search($params)
    {
        $query = ClinicLink::find()->joinWith(['user']);

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
        ]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
