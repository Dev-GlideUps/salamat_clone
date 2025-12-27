<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `clinic\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    public $cpr;
    public $name;
    public $phone;
    public $to;
    public $from;
    public $check;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'branch_id', 'check'], 'integer'],
            [['phone'], 'number'],
            [['cpr', 'name'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['to', 'from'], 'date', 'format' => 'php:Y-m-d'],
            [['has_insurance'], 'boolean'],
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
            'cpr' => Yii::t('patient', 'Patient CPR'),
            'name' => Yii::t('patient', 'Patient Name'),
            'phone' => Yii::t('patient', 'Patient Phone'),
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
        $query = Invoice::find()->alias('i')->joinWith(['branch b', 'patient p'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic]);

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
            'i.id' => $this->id,
            'i.branch_id' => $this->branch_id,
            'i.has_insurance' => $this->has_insurance,
        ]);

        if (!empty($this->created_at)) {
            $start = strtotime($this->created_at);
            $end = strtotime("+1 day", $start);

            $query->andFilterWhere (['AND',
                ['>=', 'i.created_at', $start],
                ['<', 'i.created_at', $end],
            ]);
        }

        $query->andFilterWhere (['OR',
            ['like', 'p.name', $this->name],
            ['like', 'p.name_alt', $this->name],
        ]);
        $query->andFilterWhere(['like', 'p.cpr', $this->cpr])
            ->andFilterWhere(['like', 'p.phone', $this->phone]);

        return $dataProvider;
    }

    public function search_date($params)
    {
        $query = Invoice::find()->alias('i')->joinWith(['branch b', 'patient p'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic]);;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }
        // echo "<pre>";print_r($params);die;

        // grid filtering conditions
        $query->andFilterWhere([
            'i.id' => $this->id,
            'i.branch_id' => $this->branch_id,
            'i.has_insurance' => $this->has_insurance,
        ]);

        if (!empty($this->created_at)) {
            $start = strtotime($this->created_at);
            $end = strtotime("+1 day", $start);

            $query->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', $start],
                ['<', 'i.created_at', $end],
            ]);
        }

        if (!empty($this->to) && !empty($this->from) && $this->check == 4) {
            // echo "<pre>";print_r($this->start_date);die;

            $to = strtotime($this->to);
            $from = strtotime($this->from);

            $query->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', $from],
                ['<', 'i.created_at', $to],
            ]);
        }
        if ($this->check == 1) {
            $lastDate = date('Y-m-d', strtotime("- 1 day"));
            $curDate = date('Y-m-d');
            $query->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastDate)],
                ['<', 'i.created_at', strtotime($curDate)],
            ]);
        }
        if ($this->check == 2) {
            // echo "<pre>";print_r($params);die;

            $lastDate = date('Y-m-d', strtotime("- 7 day"));
            //             echo "<pre>";print_r($lastDate);
            // echo "<pre>";print_r($params);die;
            $curDate = date('Y-m-d');
            $query->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastDate)],
                ['<', 'i.created_at', strtotime($curDate)],
            ]);
        }
        if ($this->check == 3) {
            // echo "<pre>";print_r($params);die;
            $lastmonth = date('Y-m-d', strtotime("-30 day"));
            // $lastmonth = date('Y-m-01');

            $curDate = date('Y-m-d');
            $query->andFilterWhere([
                'AND',
                ['>=', 'i.created_at', strtotime($lastmonth)],
                ['<', 'i.created_at', strtotime($curDate)],
            ]);
        }


        $query->andFilterWhere([
            'OR',
            ['like', 'p.name', $this->name],
            ['like', 'p.name_alt', $this->name],
        ]);
        $query->andFilterWhere(['like', 'p.cpr', $this->cpr])
            ->andFilterWhere(['like', 'p.phone', $this->phone]);
        // echo "<pre>";print_r($dataProvider);die;
        return $dataProvider;
    }


}
