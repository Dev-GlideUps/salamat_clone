<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\DoctorClinicBranch;

/**
 * AppointmentScheduleSearch represents the model behind the search form of `clinic\models\DoctorClinicBranch`.
 */
class AppointmentScheduleSearch extends DoctorClinicBranch
{
    public $date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
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

    public function attributeLabels()
    {
        return array_merge([
        ], parent::attributeLabels());
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
        $query = DoctorClinicBranch::find()->alias('dcb')->joinWith([
            'branch b',
            'doctor d',
            'appointments app' => function (\yii\db\ActiveQuery $query) {
                // add appointment conditions here to prevent LEFT JOIN from becoming INNER JOIN
                $query->joinWith(['patient p', 'invoice i'])->onCondition([
                    'app.date' => $this->date,
                ]);
            },
        ])->orderBy([
            'dcb.branch_id' => SORT_ASC,
            'dcb.doctor_id' => SORT_ASC,
            'app.date' => SORT_ASC,
            'app.time' => SORT_ASC,
            'app.created_at' => SORT_ASC,
        ]);

        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
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
            'dcb.branch_id' => $this->branch_id,
        ]);

        return $dataProvider;
    }
}
