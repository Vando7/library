<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LentTo;
use yii\helpers\VarDumper;


class LentToSearch extends LentTo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_isbn','user_id','employee_id','amount','date_lent','date_returned','deadline','status','statusQuery'], 'safe'],
            [['amount'], 'integer'],
        ];
    }


    /**
     * Status query variable 
     * @var statusQuery
     */
    public $statusQuery = "";


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
        $query = LentTo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'status',   $this->statusQuery]);
        
        return $dataProvider;
    }
}
