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
    public function search($params, $user_id = NULL, $isMyBooks = false)
    {
        $query = LentTo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_lent' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['bookIsbn','user', 'employee']);

        // User querying their own history
        if($user_id != NULL) {
            $query->andFilterWhere(['=','user_id',$user_id]);
        }

        // Checking only books that have to be Returned
        if($isMyBooks && $this->statusQuery === ''){
            $query->andFilterWhere(['like', 'status',   'taken']);
        } // Filter by status
        else if($this->statusQuery === 'late'){ 
            $query->andFilterWhere([
                'AND',
                ['like', 'status',   'taken'],
                ['<','deadline',date("Y-m-d H:i:s"),],
            ]);
        }
        else if($this->statusQuery === 'taken'){
            $query->andFilterWhere([
                'AND',
                ['like', 'status',   'taken'],
                ['>','deadline',date("Y-m-d H:i:s"),],
            ]);
        }
        else{
            $query->andFilterWhere(['like', 'status',   $this->statusQuery]);
        }
        
        return $dataProvider;
    }


    public function searchProblemDays($params)
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

        $query->joinWith(['bookIsbn','user', 'employee']);

        
        $query->select(['user_id','count(*) as count','book_isbn','employee_id','status'])
                ->from('lent_to')
                ->andFilterWhere(['like','status','taken'])
                ->groupBy('user_id')
                ->orderBy(['count'=>SORT_DESC])->all();

        
        
        return $dataProvider;
    }


    public function searchProblemLate($params)
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

        $query->joinWith(['user']);

        $query->select(['user_id','min(deadline) as deadline','book_isbn','employee_id','status',])
                ->from('lent_to')
                ->andFilterWhere(['and',
                    ['like','status','taken'],
                ])
                ->groupBy('user_id')
                ->orderBy(['min(deadline)'=>SORT_ASC])->all();

        
        
        return $dataProvider;
    }
    
}
