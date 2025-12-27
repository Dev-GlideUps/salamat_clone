<?php

namespace clinic\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use clinic\models\InvoicePayment;

/**
 * InvoicePaymentSearch represents the model behind the search form of `clinic\models\InvoicePayment`.
 */
class InvoicePaymentSearch extends InvoicePayment
{
    public $cpr;
    public $name;
    public $phone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'payment_method'], 'integer'],
            [['phone'], 'number'],
            [['cpr', 'name'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
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
        $query = InvoicePayment::find()->alias('ip')->joinWith(['invoice i', 'branch b', 'patient p'])->where(['b.clinic_id' => Yii::$app->user->identity->active_clinic]);

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
            'ip.id' => $this->id,
            'ip.invoice_id' => $this->invoice_id,
            'ip.payment_method' => $this->payment_method,
        ]);

        if (!empty($this->created_at)) {
            $start = strtotime($this->created_at);
            $end = strtotime("+1 day", $start);

            $query->andFilterWhere (['AND',
                ['>=', 'ip.created_at', $start],
                ['<', 'ip.created_at', $end],
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
}
